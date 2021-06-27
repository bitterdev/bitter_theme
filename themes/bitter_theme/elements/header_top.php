<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Cookie\CookieJar;
use Concrete\Core\Html\Service\Html;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\View\View;

/** @var Page $c */
/** @var View $view */

$app = Application::getFacadeApplication();
/** @var $htmlHelper Html */
$htmlHelper = $app->make(Html::class);
/** @var Repository $config */
$config = $app->make(Repository::class);
/** @var CookieJar $cookie */
$cookie = $app->make('cookie');

$privacyPage = Page::getByID($config->get("bitter_theme.privacy_page_id"));
$hasPrivacyPage = $privacyPage instanceof Page && !$privacyPage->isError();

?>

<!DOCTYPE html>
<html lang="<?php echo Localization::activeLanguage() ?>">
<!--suppress HtmlRequiredTitleElement -->
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <?php echo $htmlHelper->css($view->getStylesheet('main.less')); ?>

    <?php
    $disableTrackingCode = false;

    if ($hasPrivacyPage) {
        $disableTrackingCode = $cookie->get("cookie_preferences") !== "accept";
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    View::element('header_required', [
        'pageTitle' => isset($pageTitle) ? $pageTitle : '',
        'pageDescription' => isset($pageDescription) ? $pageDescription : '',
        'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : '',
        'disableTrackingCode' => $disableTrackingCode
    ]);
    ?>
</head>

<body>
<div id="ccm-page-container" class="<?php echo $c->getPageWrapperClass() ?>">
    <?php if ($hasPrivacyPage) {?>
        <div class="cookie-disclosure hidden">
            <div class="info-container">
                <p class="message">
                    <?php echo t("This website uses cookies to ensure you get the best experience on our website."); ?>

                    <a href="<?php echo (string)Url::to($privacyPage); ?>">
                        <?php echo t("Learn more"); ?>
                    </a>
                </p>
            </div>

            <div class="buttons-container">
                <a href="javascript:void(0);" class="btn btn-default">
                    <?php echo t("Deny Cookies"); ?>
                </a>

                <a href="javascript:void(0);" class="btn btn-primary">
                    <?php echo t("Allow Cookies"); ?>
                </a>
            </div>
        </div>
    <?php } ?>