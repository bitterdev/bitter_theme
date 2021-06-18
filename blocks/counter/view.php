<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

?>

<?php if (is_object(Page::getCurrentPage()) && Page::getCurrentPage()->isEditMode()): ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Counter Up is disabled in edit mode.') ?>
    </div>
<?php else: ?>
    <div class="counter count-of-items-<?php echo count($items); ?>"
         data-duration="<?php echo intval($time); ?>"
         style="background-color: <?php echo $backgroundColor; ?>; color: <?php echo $textColor; ?>;">
        
        <div class="counter-items">
            <?php foreach ($items as $item): ?>
                <div class="counter-item">
                    <span class="counter-value">
                        <?php echo intval($item->getCounterValue()); ?>
                    </span>

                    <p>
                        <?php echo $item->getCounterDescription(); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>