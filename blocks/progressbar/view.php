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
        <?php echo t('Progressbar is disabled in edit mode.') ?>
    </div>
<?php else: ?>
    <div class="progressbar-addon-container <?php echo $isThick ? "thick" : ""; ?> <?php echo $hasAnimation ? 'animated' : ''; ?>" 
         data-duration="<?php echo $animationDuration; ?>"
         data-value="<?php echo $value; ?>">
        <?php if($isInlineLabel): ?>
            <div class="progressbar inline" style="background-color: <?php echo $backgroundColor; ?>">
                <div class="progressbar-filled" style="background-color: <?php echo $barColor; ?>; width: <?php echo $value; ?>%;"></div>
                
                <span class="progressbar-label" style="color: <?php echo $labelColor; ?>">
                    <?php echo $label; ?>
                </span>
            </div>
        <?php else: ?>
            <span class="progressbar-label" style="color: <?php echo $labelColor; ?>">
                <?php echo $label; ?>
            </span>
        
            <div class="progressbar <?php echo $isThick ? "thick" : "thin"; ?>" style="background-color: <?php echo $backgroundColor; ?>">
                <div class="progressbar-filled" style="background-color: <?php echo $barColor; ?>; width: <?php echo $value; ?>%;"></div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>