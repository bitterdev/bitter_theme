<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Page\Page;

/** @var string $label */
/** @var int $value */
/** @var int $duration */

$c = Page::getCurrentPage();
?>

<?php if ($c instanceof Page && $c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Progressbar is disabled in edit mode.') ?>
    </div>
<?php } else { ?>
    <div class="progressbar"
         data-duration="<?php echo h($duration); ?>"
         data-target-value="<?php echo h($value); ?>">

        <?php echo $label; ?>

        <div class="value">
            <div class="filled"></div>
        </div>
    </div>
<?php } ?>