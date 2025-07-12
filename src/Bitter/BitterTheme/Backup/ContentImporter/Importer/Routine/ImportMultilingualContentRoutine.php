<?php /** @noinspection PhpUnused */

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

namespace Bitter\BitterTheme\Backup\ContentImporter\Importer\Routine;

use Concrete\Core\Backup\ContentImporter\Importer\Routine\AbstractPageContentRoutine;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Entity\Site\Locale;
use Concrete\Core\Localization\Locale\Service;
use Concrete\Core\Multilingual\Page\Section\Section;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Stack\Stack;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\EntityManager;
use SimpleXMLElement;

class ImportMultilingualContentRoutine extends AbstractPageContentRoutine
{
    public function getHandle(): string
    {
        return 'multilingual_content';
    }

    public function import(SimpleXMLElement $element)
    {
        $app = Application::getFacadeApplication();
        /** @var Service $service */
        $service = $app->make(Service::class);
        $app = Application::getFacadeApplication();
        /** @var EntityManager $entityManager */
        $entityManager = $app->make(EntityManager::class);
        /** @var Connection $db */
        $db = $app->make(Connection::class);
        /** @var \Concrete\Core\Site\Service $siteService */
        $siteService = $app->make('site');
        $site = $siteService->getSite();
        $locales = [];

        /*
         * Import Locales
         */

        if (isset($element->multilingual->locales)) {
            foreach ($element->multilingual->locales->locale as $item) {
                /** @var Locale $locale */
                $locale = $entityManager->getRepository(Locale::class)->findOneBy([
                    'site' => $site,
                    'msLanguage' => (string)$item["language"],
                    'msCountry' => (string)$item["country"]
                ]);

                if (!$locale instanceof Locale) {
                    $service->add($site, (string)$item["language"], (string)$item["country"]);
                }
            }
        }

        /*
         * Map Pages
         */

        if (isset($element->multilingual->mappages)) {
            foreach ($element->multilingual->mappages->mappage as $item) {
                $sourcePage = Page::getByPath((string)$item["source-path"]);
                $destPage = Page::getByPath((string)$item["target-path"]);
                $targetPageLocale = (string)$item["target-path-locale"];
                $isHomePage = ((int)$item["is-home-page"] === 1);
                $locale = explode("_", $targetPageLocale);

                /** @var Locale $locale */
                $locale = $entityManager->getRepository(Locale::class)->findOneBy([
                    'site' => $site,
                    'msLanguage' => (string)$locale[0],
                    'msCountry' => (string)$locale[1]
                ]);

                $locales[$targetPageLocale] = $locale;

                if ($locale instanceof Locale) {
                    $siteTreeId = $locale->getSiteTreeID();
                    /** @noinspection PhpUnhandledExceptionInspection */
                    /** @noinspection SqlDialectInspection */
                    /** @noinspection SqlNoDataSourceInspection */
                    $db->executeQuery("UPDATE Pages SET siteTreeID = ? WHERE cID = ?", [$siteTreeId, $destPage->getCollectionID()]);
                }

                if (Section::isMultilingualSection($destPage)) {
                    $ms = Section::getByID($destPage->getCollectionID());
                } else {
                    $ms = Section::getBySectionOfSite($destPage);
                }

                if (is_object($ms)) {
                    if (!Section::isAssigned($sourcePage)) {
                        Section::registerPage($sourcePage);
                    }

                    Section::relatePage($sourcePage, $destPage, $targetPageLocale);

                    if ($isHomePage) {
                        $locale = explode("_", $targetPageLocale);
                        /** @var Locale $locale */
                        $locale = $entityManager->getRepository(Locale::class)->findOneBy([
                            'site' => $site,
                            'msLanguage' => $locale[0],
                            'msCountry' => $locale[1]
                        ]);
                        $tree = $locale->getSiteTree();
                        $tree->setLocale($locale);
                        $tree->setSiteHomePageID($destPage->getCollectionID());
                        /** @noinspection PhpUnhandledExceptionInspection */
                        $entityManager->persist($tree);
                        /** @noinspection PhpUnhandledExceptionInspection */
                        $entityManager->flush();
                        $destPage->moveToRoot();
                        $destPage->rescanCollectionPath();

                        // Copy the permissions from the canonical home page to this home page.
                        $homeCID = Page::getHomePageID();
                        if ($homeCID !== null) {
                            $destPage->acquirePagePermissions($homeCID);
                        }
                    }
                }
            }

            // create multilingual versions of all stacks
            foreach ($locales as $locale) {
                /** @noinspection SqlDialectInspection */
                /** @noinspection SqlNoDataSourceInspection */
                foreach ($db->fetchAll("SELECT cID, stName FROM Stacks") as $row) {
                    $neutralStack = Stack::getByID((int)$row["cID"]);
                    if (is_object($neutralStack)) {
                        $section = Section::getByLocale($locale->getLocale());
                        $localizedStack = $neutralStack->addLocalizedStack($section);

                        /*
                         * Translate Stacks
                         */

                        if (isset($element->multilingual->stacks)) {
                            foreach ($element->multilingual->stacks->stack as $item) {
                                $stackName = (string)$item["name"];
                                $stackLocale = (string)$item["locale"];

                                if ($stackName == $row["stName"] &&
                                    $stackLocale == $locale->getLocale()) {

                                    // Okay we have a stack that needs to be translated...
                                    $blocks = $localizedStack->getBlocks('Main');

                                    foreach ($blocks as $block) {
                                        // delete all old blocks
                                        $block->deleteBlock();
                                    }

                                    // and finally let's import the translated stack content for this locale
                                    $this->importPageAreas($localizedStack, $item);

                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
