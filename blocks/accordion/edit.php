<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Editor\EditorInterface;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;

/** @var array $items */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var EditorInterface $editor */
$editor = $app->make(EditorInterface::class);

?>

<div id="items-container"></div>

<script id="item-template" type="text/template">
    <div class="accordion-item">
        <div class="form-group">
            <label for="title-<%=id%>">
                <?php echo t("Title"); ?>
            </label>

            <input id="title-<%=id%>" type="text" name="items[<%=id%>][title]" value="<%=title%>" class="form-control"/>
        </div>

        <div class="form-group">
            <label for="body-<%=id%>">
                <?php echo t("Body"); ?>
            </label>

            <textarea id="body-<%=id%>" name="items[<%=id%>][body]" class="form-control"><%=body%></textarea>
        </div>

        <a href="javascript:void(0);" class="btn btn-danger">
            <?php echo t("Remove Item"); ?>
        </a>
    </div>
</script>

<a href="javascript:void(0);" class="btn btn-primary" id="ccm-add-item">
    <?php echo t("Add Item"); ?>
</a>

<style type="text/css">
    .accordion-item {
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

        var addItem = function (title, body) {
            var $item = $(_.template($("#item-template").html())({
                id: nextInsertId,
                title: title,
                body: body
            }));

            nextInsertId++;

            $item.find(".btn-danger").click(function () {
                $(this).parent().remove();
            });

            $("#items-container").append($item);

            CKEDITOR.replace($("#items-container").find("textarea").last().get(0));
        };

        for (var item of items) {
            addItem(item.title, item.body);
        }

        $("#ccm-add-item").click(function (e) {
            e.preventDefault();
            addItem("", "");
            return true;
        });
    })(jQuery);
</script>