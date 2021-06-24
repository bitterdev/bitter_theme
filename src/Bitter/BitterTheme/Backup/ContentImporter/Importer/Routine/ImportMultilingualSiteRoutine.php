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
use Concrete\Core\Localization\Locale\Service;
use Concrete\Core\Page\Template;
use Concrete\Core\Support\Facade\Application;
use SimpleXMLElement;

class ImportMultilingualSiteRoutine extends AbstractRoutine
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

        if (isset($element->multilingualsites)) {
            foreach ($element->multilingualsites->multilingualsite as $item) {
                $locale = $service->add($site, (string)$item["language"], (string)$item["country"]);
                $template = Template::getByHandle((string)$item["template"]);
                $service->addHomePage($locale, $template, (string)$item["page-name"], (string)$item["url-slug"]);
            }
        }

    }
}
