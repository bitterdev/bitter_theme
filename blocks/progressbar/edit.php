<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;

/** @var string $label */
/** @var int $value */
/** @var int $duration */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);

\Concrete\Core\View\View::element("dashboard/help_blocktypes", [], "bitter_theme");

/** @noinspection PhpUnhandledExceptionInspection */
\Concrete\Core\View\View::element("dashboard/did_you_know", [], "bitter_theme");
?>

<div class="edit-settings-form">
    <div class="form-group">
        <?php echo $form->label("label", t("Label")); ?>
        <?php echo $form->text("label", $label); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("value", t("Value")); ?>

        <div class="input-group">
            <?php echo $form->number("value", $value); ?>

            <span class="input-group-text" id="basic-addon2">
                <?php echo t("%"); ?>
            </span>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->label("duration", t("Animation Duration")); ?>

        <div class="input-group">
            <?php echo $form->number("duration", $duration); ?>

            <span class="input-group-text" id="basic-addon2">
                <?php echo t("ms"); ?>
            </span>
        </div>
    </div>
</div>
