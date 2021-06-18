<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

View::element('/dashboard/Help', null, 'masonry_grid');

View::element('/dashboard/Reminder', array("packageHandle" => "masonry_grid", "rateUrl" => "https://www.concrete5.org/marketplace/addons/masonry-grid/reviews"), 'masonry_grid');

\Concrete\Core\View\View::element('/dashboard/license_check', array("packageHandle" => "masonry_grid"), 'masonry_grid');

echo Core::make('helper/concrete/ui')->tabs(array(
    array('file-sets', t('File Sets'), true),
    array('options', t('Options'))
));

$colorHelperDefaults = array(
    'className' => 'ccm-widget-colorpicker',
    'showInitial' => true,
    'showInput' => true,
    'cancelText' => t('Cancel'),
    'chooseText' => t('Choose'),
    'preferredFormat' => 'hex',
    'clearText' => t('Clear Color Selection')
);

$colorHelper = Core::make('helper/form/color');

?>

<div id="ccm-tab-content-file-sets" class="ccm-tab-content">
    <?php if(count($fileSets) === 0): ?>
        <div class="alert alert-warning">
            <?php echo t("You don't have any file sets. Click <a href=\"%s\">here</a> to create one.", $this->url("/dashboard/files/add_set")); ?>
        </div>
    <?php else: ?>
        <?php foreach($fileSets as $fileSetId => $fileSetName): ?>
            <div class="checkbox">
                <label>
                    <input name="fileSets[]" type="checkbox" value="<?php echo $fileSetId; ?>" <?php echo in_array($fileSetId, $selectedFileSets) ? " checked=\"checked\"" : ""; ?>>

                    <?php echo $fileSetName; ?>
                </label>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div id="ccm-tab-content-options" class="ccm-tab-content">
    <div class="form-group">
        <?php echo $form->label("backgroundColorNormal", t("Background Color")); ?>
        <?php echo $colorHelper->output("backgroundColorNormal", $backgroundColorNormal, $colorHelperDefaults); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("textColorNormal", t("Text color")); ?>
        <?php echo $colorHelper->output("textColorNormal", $textColorNormal, $colorHelperDefaults); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("backgroundColorActive", t("Background Color (Active)")); ?>
        <?php echo $colorHelper->output("backgroundColorActive", $backgroundColorActive, $colorHelperDefaults); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("textColorActive", t("Text color (Active)")); ?>
        <?php echo $colorHelper->output("textColorActive", $textColorActive, $colorHelperDefaults); ?>
    </div>

    <div class="checkbox">
        <label>
            <?php echo $form->checkbox("disableNoDescription", 1, $disableNoDescription); ?>
            <?php echo t("Disable no description notice"); ?>
        </label>
    </div>

    <div class="checkbox">
        <label>
            <?php echo $form->checkbox("disableViewAll", 1, $disableViewAll); ?>
            <?php echo t("Disable View All Button"); ?>
        </label>
    </div>
</div>

<?php \Concrete\Core\View\View::element('/dashboard/did_you_know', array("packageHandle" => "masonry_grid"), 'masonry_grid'); ?>
