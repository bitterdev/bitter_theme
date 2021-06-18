<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

View::element('/dashboard/Help', null, 'counter');

View::element('/dashboard/Reminder', ["packageHandle" => "counter", "rateUrl" => "https://www.concrete5.org/marketplace/addons/counter/reviews"], 'counter');

$defaults = array(
    'className' => 'ccm-widget-colorpicker',
    'showInitial' => true,
    'showInput' => true,
    'cancelText' => t('Cancel'),
    'chooseText' => t('Choose'),
    'preferredFormat' => 'hex',
    'clearText' => t('Clear Color Selection')
);

$tabs = array(
    array('items', t('Items'), true),
    array('options', t('Options'))
);

echo Core::make('helper/concrete/ui')->tabs($tabs);

?>

<?php \Concrete\Core\View\View::element('/dashboard/license_check', array("packageHandle" => "counter"), 'counter'); ?>

<div id="ccm-tab-content-items" class="ccm-tab-content">
    <div id="itemsContainer">
        &nbsp;
    </div>

    <div>
        <a href="javascript:void(0);" id="addItem" class="btn btn-success">
            <?php echo t("Add Item"); ?>
        </a>
    </div>
</div>

<div id="ccm-tab-content-options" class="ccm-tab-content">
    <div class="form-group">
        <?php echo $form->label("textColor", t("Text color")); ?>
        <?php echo Core::make('helper/form/color')->output("textColor", ($textColor == "" ? "#000000" : $textColor), $defaults); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("backgroundColor", t("Background color")); ?>
        <?php echo Core::make('helper/form/color')->output("backgroundColor", ($backgroundColor == "" ? "transparent" : $backgroundColor), $defaults); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("time", t("Time")); ?>

        <div class="input-group">
            <?php echo $form->number("time", ($time == "" ? 1000 : $time)); ?>

            <span class="input-group-addon" id="basic-addon2">
                <?php echo t("ms"); ?>
            </span>
        </div>

        <p class="help-block">
            <?php echo t("The total duration of the count up animation."); ?>
        </p>
    </div>
</div>

<script id="itemTemplate" type="x-tmpl-mustache">
    <div id="item-{{id}}" class="item">
        <div class="form-group">
            <?php echo $form->label("counterValue", t("Value")); ?>
            <?php echo $form->number("items[{{id}}][counterValue]", "{{value}}"); ?>
        </div>

        <div class="form-group">
            <?php echo $form->label("counterDescription", t("Description")); ?>
            <?php echo $form->text("items[{{id}}][counterDescription]", "{{description}}"); ?>
        </div>

        <a href="javascript:void(0);" class="btn btn-danger" onclick="return counter.backend.removeItem({{id}});">
            <?php echo t("Remove Item"); ?>
        </a>
    </div>
</script>

<style type="text/css">
    .item {
        min-height: 20px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        counter.backend.init(<?php echo json_encode($items, true); ?>);
    });
</script>

<?php \Concrete\Core\View\View::element('/dashboard/did_you_know', array("packageHandle" => "counter"), 'counter'); ?>
