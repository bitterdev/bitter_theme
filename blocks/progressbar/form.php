<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

View::element('/dashboard/Help', null, 'progressbar');

View::element('/dashboard/Reminder', array("packageHandle" => "progressbar", "rateUrl" => "https://www.concrete5.org/marketplace/addons/progressbar/reviews"), 'progressbar');

$defaults = array(
    'className' => 'ccm-widget-colorpicker',
    'showInitial' => true,
    'showInput' => true,
    'cancelText' => t('Cancel'),
    'chooseText' => t('Choose'),
    'preferredFormat' => 'hex',
    'clearText' => t('Clear Color Selection')
);

?>

<?php \Concrete\Core\View\View::element('/dashboard/license_check', array("packageHandle" => "progressbar"), 'progressbar'); ?>

<div class="edit-settings-form">
    <div class="form-group">
        <?php echo $form->label("label", t("Label")); ?>
        <?php echo $form->text("label", $label); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("value", t("Value")); ?>

        <div class="input-group">
            <?php echo $form->number("value", $value); ?>

            <span class="input-group-addon" id="basic-addon2">
                <?php echo t("%"); ?>
            </span>
        </div>
    </div>

    <hr>

    <div class="form-group">
        <?php echo $form->label("labelColor", t("Label color")); ?>
        <?php echo Core::make('helper/form/color')->output("labelColor", ($labelColor == "" ? "#000000" : $labelColor), $defaults); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("barColor", t("Bar color")); ?>
        <?php echo Core::make('helper/form/color')->output("barColor", ($barColor == "" ? "#868686" : $barColor), $defaults); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("backgroundColor", t("Background color")); ?>
        <?php echo Core::make('helper/form/color')->output("backgroundColor", ($backgroundColor == "" ? "transparent" : $backgroundColor), $defaults); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("isInlineLabel", t("Label Position")); ?>
        <?php echo $form->select("isInlineLabel", array(0 => t("Above"), 1 => t("Inline")), $isInlineLabel); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("isThick", t("Thickness")); ?>
        <?php echo $form->select("isThick", array(0 => t("Thin"), 1 => t("Thick")), $isThick); ?>
    </div>

    <hr>

    <div class="form-group">
        <?php echo $form->label("hasAnimation", t("Animate")); ?>
        <?php echo $form->select("hasAnimation", array(0 => t("No"), 1 => t("Yes")), $hasAnimation); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("animationDuration", t("Animation duration")); ?>

        <div class="input-group">
            <?php echo $form->number("animationDuration", is_null($animationDuration) ? 300 : $animationDuration); ?>

            <span class="input-group-addon" id="basic-addon2">
                <?php echo t("ms"); ?>
            </span>
        </div>

        <p class="help-block">
            <?php echo t("The total duration of the animation."); ?>
        </p>
    </div>
</div>

<?php \Concrete\Core\View\View::element('/dashboard/did_you_know', array("packageHandle" => "progressbar"), 'progressbar'); ?>
