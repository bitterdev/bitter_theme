<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Html\Service\Html;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\View\View;

/** @var Page $c */
/** @var View $view */

$app = Application::getFacadeApplication();
/** @var $htmlHelper Html */
$htmlHelper = $app->make(Html::class);
/** @var Repository $config */
$config = $app->make(Repository::class);
$site = $app->make('site')->getSite();
$config = $site->getConfigRepository();
$privacyPage = Page::getByID($config->get("bitter_theme.privacy_page_id"));
$hasPrivacyPage = $privacyPage instanceof Page && !$privacyPage->isError();
$language = substr(Localization::getInstance()->getLocale(), 0, 2);
$siteName = $site->getSiteName();
$cookieTable = $config->get("bitter_theme.cookie_table", []) || [];

?>
<!DOCTYPE html>
<html lang="<?php echo Localization::activeLanguage() ?>">
<!--suppress HtmlRequiredTitleElement -->
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <?php echo $view->getThemeStyles(); ?>
    <?php echo $htmlHelper->css($view->getThemePath() . "/css/fonts.css"); ?>

    <?php
    /** @noinspection PhpUnhandledExceptionInspection */
    View::element('header_required', [
        'pageTitle' => $pageTitle ?? '',
        'pageDescription' => $pageDescription ?? '',
        'pageMetaKeywords' => $pageMetaKeywords ?? ''
    ]);
    ?>
</head>

<body>
<div id="ccm-page-container" class="<?php echo $c->getPageWrapperClass() ?>">

    <script>
        window.bitterThemeConfig = <?php echo json_encode([
            "header" => [
                "title" => $siteName
            ],
            "iframeManager" => [
                "language" => $language,
                "languages" => [
                    $language => [
                        "notice" => t("This content is hosted by 3rd party. By clicking the view maps button you agree to the <a rel=\"noreferrer noopener\" href=\"https://cloud.google.com/maps-platform/terms\" target=\"_blank\">privacy policy</a> of Google Maps."),
                        "loadBtn" => t("Display Map"),
                        "loadAllBtn" => t("Always Display")
                    ]
                ]
            ]
        ]);  ?>
    </script>