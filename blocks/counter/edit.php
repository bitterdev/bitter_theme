<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Application\Service\UserInterface;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;

/** @var int $duration */
/** @var array $items */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var UserInterface $ui */
$ui = $app->make(UserInterface::class);

/** @noinspection PhpParamsInspection */
echo $ui->tabs([
    ['items', t('Items'), true],
    ['options', t('Options')],
]);
?>

<div class="tab-content">
    <div id="items" class="tab-pane active">
        <div id="items-container"></div>

        <a href="javascript:void(0);" class="btn btn-primary" id="ccm-add-item">
            <?php echo t("Add Item"); ?>
        </a>
    </div>

    <div id="options" class="tab-pane">
        <div class="form-group">
            <?php echo $form->label("duration", t("Duration")); ?>
            <?php echo $form->number("duration", $duration, ["min" => 0]); ?>
        </div>
    </div>
</div>

<script id="item-template" type="text/template">
    <div class="counter-item">
        <div class="form-group">
            <label for="value-<%=id%>">
                <?php echo t("Value"); ?>
            </label>

            <input id="value-<%=id%>" type="text" name="items[<%=id%>][value]" value="<%=value%>" class="form-control"/>
        </div>

        <div class="form-group">
            <label for="description-<%=id%>">
                <?php echo t("Description"); ?>
            </label>

            <textarea id="description-<%=id%>" name="items[<%=id%>][description]"
                      class="form-control"><%=description%></textarea>
        </div>

        <a href="javascript:void(0);" class="btn btn-danger">
            <?php echo t("Remove Item"); ?>
        </a>
    </div>
</script>

<style type="text/css">
    .counter-item {
        border: 1px solid #dadada;
        background: #f9f9f9;
        padding: 15px;
        margin-bottom: 15px;
    }
</style>

<script type="text/javascript">
    (function ($) {
        var nextInsertId = 0;
        var items = <?php echo json_encode($items);?>;

        var addItem = function (value, description) {
            var $item = $(_.template($("#item-template").html())({
                id: nextInsertId,
                value: value,
                description: description
            }));

            nextInsertId++;

            $item.find(".btn-danger").click(function () {
                $(this).parent().remove();
            });

            $("#items-container").append($item);
        };

        for (var item of items) {
            addItem(item.value, item.description);
        }

        $("#ccm-add-item").click(function (e) {
            e.preventDefault();
            addItem("", "");
            return true;
        });
    })(jQuery);
</script>