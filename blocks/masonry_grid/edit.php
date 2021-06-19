<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

use Concrete\Core\Support\Facade\Url;

defined('C5_EXECUTE') or die('Access denied');

/** @var array $fileSets */
/** @var array $selectedFileSets */
?>

<?php if (count($fileSets) === 0): ?>
    <div class="alert alert-warning">
        <?php echo t("You don't have any file sets. Click %s to create one.", sprintf("<a href=\"%s\">%s</a>", Url::to("/dashboard/files/add_set"), t("here"))); ?>
    </div>
<?php else: ?>
    <?php foreach ($fileSets as $fileSetId => $fileSetName): ?>
        <div class="checkbox">
            <label>
                <input name="fileSets[]" type="checkbox"
                       value="<?php echo h($fileSetId); ?>" <?php echo in_array($fileSetId, $selectedFileSets) ? " checked=\"checked\"" : ""; ?>>

                <?php echo $fileSetName; ?>
            </label>
        </div>
    <?php endforeach; ?>
<?php endif; ?>