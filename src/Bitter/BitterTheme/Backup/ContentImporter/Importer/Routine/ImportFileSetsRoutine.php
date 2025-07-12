<?php /** @noinspection PhpUnused */

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

namespace Bitter\BitterTheme\Backup\ContentImporter\Importer\Routine;

use Concrete\Core\Backup\ContentImporter\Importer\Routine\AbstractRoutine;
use Concrete\Core\Backup\ContentImporter\ValueInspector\ValueInspector;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\File;
use Concrete\Core\File\Set\Set;
use Concrete\Core\Support\Facade\Application;
use SimpleXMLElement;

class ImportFileSetsRoutine extends AbstractRoutine
{
    public function getHandle(): string
    {
        return 'file_sets';
    }

    public function import(SimpleXMLElement $element)
    {
        $app = Application::getFacadeApplication();
        /** @var ValueInspector $valueInspector */
        $valueInspector = $app->make('import/value_inspector');

        if (isset($element->filesets)) {
            foreach ($element->filesets->fileset as $item) {
                $fileSet = Set::getByName((string)$item["name"]);

                if (!$fileSet instanceof Set) {
                    $fileSet = Set::createAndGetSet((string)$item["name"], Set::TYPE_PUBLIC, true);
                }

                if (isset($item->files)) {
                    foreach ($item->files->children() as $fileItem) {
                        $file = File::getByID($valueInspector->inspect((string)$fileItem)->getReplacedValue());

                        if ($file instanceof \Concrete\Core\Entity\File\File) {
                            $fileVersion = $file->getApprovedVersion();

                            if ($fileVersion instanceof Version) {
                                $fileSet->addFileToSet($fileVersion);
                            }
                        }
                    }
                }
            }
        }
    }
}
