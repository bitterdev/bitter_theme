<?php /** @noinspection PhpInconsistentReturnPointsInspection */
/** @noinspection PhpUnused */

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

namespace Bitter\BitterTheme\Backup\ContentImporter\ValueInspector\Item;


use Concrete\Core\Backup\ContentImporter\ValueInspector\Item\AbstractItem;
use Concrete\Core\File\Set\Set;

class FileSetItem extends AbstractItem
{
    public function getDisplayName()
    {
        return t('File Set');
    }

    /**
     * @return Set
     */
    public function getContentObject()
    {
        return Set::getByName($this->getReference());
    }

    public function getFieldValue()
    {
        if ($fileSet = $this->getContentObject()) {
            return $fileSet->getFileSetID();
        }
    }
}
