<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\PageSelector;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;
use Concrete\Core\View\View;

/** @var int $regularLogoFileId */
/** @var int $smallLogoFileId */
/** @var int $privacyPageId */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var Token $token */
$token = $app->make(Token::class);
/** @var FileManager $fileManager */
$fileManager = $app->make(FileManager::class);
/** @var PageSelector $pageSelector */
$pageSelector = $app->make(PageSelector::class);

/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/help', null, 'bitter_theme');
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/reminder', ["packageHandle" => "bitter_theme", "rateUrl" => "https://www.concrete5.org/marketplace/themes/bitter-theme/reviews"], 'bitter_theme');
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/license_check', ["packageHandle" => "bitter_theme"], 'bitter_theme');

?>

<form action="#" method="post">´
    <?php echo $token->output("update_settings"); ?>

    <fieldset>
        <legend>
            <?php echo t("General"); ?>
        </legend>

        <div class="form-group">
            <?php echo $form->label("regularLogoFileId", t("Logo")); ?>
            <?php echo $fileManager->image("regularLogoFileId", "regularLogoFileId", t("Please select a file"), $regularLogoFileId); ?>
        </div>

        <div class="form-group">
            <?php echo $form->label("smallLogoFileId", t("Logo Small")); ?>
            <?php echo $fileManager->image("smallLogoFileId", "smallLogoFileId", t("Please select a file"), $smallLogoFileId); ?>
        </div>

        <div class="form-group">
            <?php echo $form->label("privacyPageId", t("Privacy Page")); ?>
            <?php echo $pageSelector->selectPage("privacyPageId", $privacyPageId); ?>
        </div>
    </fieldset>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <?php echo $form->submit('save', t('Save'), ['class' => 'btn btn-primary pull-right']); ?>
        </div>
    </div>
</form>

<?php
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/did_you_know', ["packageHandle" => "bitter_theme"], 'bitter_theme');