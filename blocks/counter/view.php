<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Page\Page;

/** @var int $duration */
/** @var array $items */

$c = Page::getCurrentPage();

?>

<?php if ($c instanceof Page && $c->isEditMode()): ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Counter is disabled in edit mode.') ?>
    </div>
<?php else: ?>
    <div class="counter-container" data-duration="<?php echo h($duration); ?>">
        <?php foreach ($items as $item): ?>
            <div class="counter">
                <h2 class="counter-value" data-target-value="<?php echo h($item["value"]); ?>">
                    0
                </h2>

                <p>
                    <?php echo $item["description"]; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>