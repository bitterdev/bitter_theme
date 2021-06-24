<?php /** @noinspection PhpUnused */

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\BitterTheme\Backup\ContentImporter\Importer\Routine;

use Concrete\Core\Backup\ContentImporter\Importer\Routine\AbstractRoutine;
use Concrete\Core\Entity\Site\Locale;
use Concrete\Core\Multilingual\Page\Section\Section;
use Concrete\Core\Page\Page;
use Concrete\Core\Site\Service;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use SimpleXMLElement;

class ImportMultilingualMapPagesRoutine extends AbstractRoutine
{
    public function getHandle(): string
    {
        return 'multilingual_pages';
    }

    /**
     * @throws OptimisticLockException
     */
    public function import(SimpleXMLElement $element)
    {
        $app = Application::getFacadeApplication();
        /** @var EntityManager $entityManager */
        $entityManager = $app->make(EntityManager::class);
        /** @var Service $siteService */
        $siteService = $app->make('site');
        $site = $siteService->getSite();

        if (isset($element->multilingual->mappages)) {
            foreach ($element->multilingual->mappages->mappage as $item) {
                $sourcePage = Page::getByPath((string)$item["source-path"]);
                $destPage = Page::getByPath((string)$item["target-path"]);
                $targetPageLocale = (string)$item["target-path-locale"];
                $isHomePage = ((int)$item["is-home-page"] === 1);

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
                        $entityManager->persist($tree);
                        $entityManager->flush();
                    }
                }
            }
        }
    }
}
