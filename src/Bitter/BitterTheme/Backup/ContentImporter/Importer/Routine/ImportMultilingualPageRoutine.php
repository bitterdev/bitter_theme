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
use Concrete\Core\Backup\ContentImporter\ValueInspector\ValueInspector;
use Concrete\Core\Multilingual\Page\Section\Section;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use SimpleXMLElement;

class ImportMultilingualPageRoutine extends AbstractRoutine
{
    public function getHandle(): string
    {
        return 'multilingual_pages';
    }

    public function import(SimpleXMLElement $element)
    {
        $app = Application::getFacadeApplication();
        /** @var ValueInspector $valueInspector */
        $valueInspector = $app->make('import/value_inspector');

        if (isset($element->multilingualpages)) {
            foreach ($element->multilingualpages->multilingualpage as $item) {
                $sourcePage = Page::getByID($valueInspector->inspect((string)$item["source-path"])->getReplacedValue());
                $destPage = Page::getByID($valueInspector->inspect((string)$item["target-path"])->getReplacedValue());
                $targetPageLocale = (string)$item["target-path-locale"];

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
                }
            }
        }
    }
}
