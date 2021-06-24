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
use Concrete\Core\Localization\Locale\Service;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\EntityManagerInterface;
use SimpleXMLElement;

class ImportMultilingualLocalesRoutine extends AbstractRoutine
{
    public function getHandle(): string
    {
        return 'multilingual_sites';
    }

    public function import(SimpleXMLElement $element)
    {
        $app = Application::getFacadeApplication();
        /** @var Service $service */
        $service = $app->make(Service::class);
        /** @var \Concrete\Core\Site\Service $siteService */
        $siteService = $app->make('site');
        $site = $siteService->getActiveSiteForEditing();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $app->make(EntityManagerInterface::class);

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

    }
}
