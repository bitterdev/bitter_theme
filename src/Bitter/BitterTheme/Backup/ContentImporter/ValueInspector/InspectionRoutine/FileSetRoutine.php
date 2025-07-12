<?php /** @noinspection PhpUnused */

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

namespace Bitter\BitterTheme\Backup\ContentImporter\ValueInspector\InspectionRoutine;

use Bitter\BitterTheme\Backup\ContentImporter\ValueInspector\Item\FileSetItem;
use Concrete\Core\Backup\ContentImporter\ValueInspector\InspectionRoutine\AbstractRegularExpressionRoutine;

class FileSetRoutine extends AbstractRegularExpressionRoutine
{
    public function getHandle()
    {
        return 'file_set';
    }

    public function getRegularExpression()
    {
        return '/{ccm:export:file_set:(.*?)\}/i';
    }

    public function getItem($identifier)
    {
        $prefix = null;

        $fileSetName = null;

        if (strpos($identifier, ':') > -1) {
            list($prefix, $fileSetName) = explode(':', $identifier);
        } else {
            $fileSetName = $identifier;
        }

        return new FileSetItem($fileSetName, $prefix);
    }
}
