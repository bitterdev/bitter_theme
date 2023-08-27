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

/** @var bool $enableExtendedFooter */
/** @var string $phoneNumber */
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

?>

<div class="ccm-dashboard-header-buttons">
    <?php \Concrete\Core\View\View::element("dashboard/help", [], "bitter_theme"); ?>
</div>

<form action="#" method="post">´
    <?php echo $token->output("update_settings"); ?>

    <fieldset>
        <legend>
            <?php echo t("General"); ?>
        </legend>

        <div class="form-group">
            <div class="form-check">
                <?php echo $form->checkbox("enableExtendedFooter", 1, $enableExtendedFooter); ?>
                <?php echo $form->label("enableExtendedFooter", t("Enable Extended Footer"), ["class" => "form-check-label"]); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->label("phoneNumber", t("Phone Number")); ?>
            <?php echo $form->text("phoneNumber", $phoneNumber); ?>
        </div>

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
            <?php echo $form->submit('save', t('Save'), ['class' => 'btn btn-primary float-end']); ?>
        </div>
    </div>
</form>