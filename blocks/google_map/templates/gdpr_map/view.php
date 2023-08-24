<?php
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Localization\Localization;
use Concrete\Core\Page\Page;

/**
 * @var int $width
 * @var int $height
 * @var int $zoom
 * @var float $latitude
 * @var float $longitude
 * @var bool $scrollwheel
 * @var string $title
 * @var string $titleFormat
 */

$c = Page::getCurrentPage();
if ($c->isEditMode()) { ?>
    <?php
    $loc = Localization::getInstance();
    $loc->pushActiveContext(Localization::CONTEXT_UI);
    ?>

    <div class="ccm-edit-mode-disabled-item" style="width: <?php echo $width; ?>; height:  <?php echo $height; ?>">
        <div style="padding: 80px 0 0 0"><?php echo t('Google Map disabled in edit mode.') ?></div>
    </div>

    <?php $loc->popActiveContext(); ?>
<?php } else { ?>
    <?php
    if (strlen($title) > 0) {
        echo sprintf("<%s>%s</%s>", $titleFormat, $title, $titleFormat);
    }
    ?>

    <div
            data-service="googlemaps"
            data-autoscale
            style="width: <?php echo $width; ?>; height: <?php echo $height; ?>"
            data-zoom="<?php echo $zoom; ?>"
            data-latitude="<?php echo $latitude; ?>"
            data-longitude="<?php echo $longitude; ?>"
            data-scrollwheel="<?php echo (bool)$scrollwheel ? 'true' : 'false'; ?>"
            data-draggable="<?php echo (bool)$scrollwheel ? 'true' : 'false'; ?>"
    ></div>
<?php } ?>