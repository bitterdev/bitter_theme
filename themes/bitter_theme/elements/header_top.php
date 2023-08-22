<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
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

$cookieTable = [
    [
        "col1" => "_ga",
        "col2" => "gutachter-engel.de",
        "col3" => "2 Jahre"
    ],
    [
        "col1" => "_gid",
        "col2" => "gutachter-engel.de",
        "col3" => "24 Stunden"
    ]
];
?>
<!DOCTYPE html>
<html lang="<?php echo Localization::activeLanguage() ?>">
<!--suppress HtmlRequiredTitleElement -->
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <?php echo $view->getThemeStyles(); ?>
    <?php //echo $htmlHelper->css($view->getThemePath() . "/css/main.css"); ?>

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
                        "notice" => "Dieser Inhalt wird von einem Dritten gehostet. Mit der Anzeige der externen Inhalte akzeptieren Sie die <a rel=\"noreferrer noopener\" href=\"https://cloud.google.com/maps-platform/terms\" target=\"_blank\">Datenschutzbestimmungen</a> von Google Maps.",
                        "loadBtn" => t("Show Map"),
                        "loadAllBtn" => "Immer anzeigen"
                    ]
                ]
            ],
            "cookieDisclosure" => [
                "language" => $language,
                "languages" => [
                    $language => [
                        "consent_modal" => [
                            "title" => "Wir benötigen Ihre Einwilligung",
                            "description" => "Auf unserer Webseite kommen verschiedene Cookies zum Einsatz: technische, zu Marketing-Zwecken und solche zu Analyse-Zwecken; Sie können unsere Webseite grundsätzlich auch ohne das Setzen von Cookies besuchen. Hiervon ausgenommen sind die technisch notwendigen Cookies. Ihnen steht jederzeit ein Widerrufsrecht zu. Durch klicken auf <strong>Alle Akzeptieren</strong> erklären Sie sich einverstanden, dass wir die vorgenannten Cookies zu Marketing- und zu Analyse-Zwecken setzen.",
                            "primary_btn" => [
                                "text" => "Alle Akzeptieren",
                                "role" => "accept_all"
                            ],
                            "secondary_btn" => [
                                "text" => "Einstellungen",
                                "role" => "settings"
                            ],
                        ],
                        "settings_modal" => [
                            "title" => "Cookie Einstellungen",
                            "save_settings_btn" => "Speichere aktuelle Auswahl",
                            "accept_all_btn" => "Alle akzeptieren",
                            "reject_all_btn" => "Alle ablehnen",
                            "close_btn_label" => "Schließen",
                            "cookie_table_headers" => [
                                [
                                    "col1" => "Name"
                                ],
                                [
                                    "col2" => "Domain"
                                ],
                                [
                                    "col3" => "Ablaufdatum"
                                ]
                            ],
                            "blocks" => [
                                [
                                    "description" => "Weitere Information zum Umgang mit Cookies finden Sie in unseren <a class=\"cc-link\" href=\"datenschutz.html\">Datenschutzbestimmungen</a>."
                                ],
                                [
                                    "title" => "Technische notwendige Cookies",
                                    "description" => "Diese Technologien sind für die grundlegenden Funktionen der Website erforderlich.",
                                    "toggle" => [
                                        "value" => "necessary",
                                        "enabled" => true,
                                        "readonly" => true
                                    ]
                                ],
                                [
                                    "title" => "Analytics & Marketing Cookies",
                                    "description" => "Mit diesen Cookies kann die Reichweite unseres eigenen Angebots gemessen werden. Die Cookies ermöglichen es uns unter anderem zu verfolgen, welche Website vor dem Zugriff auf unsere Website besucht wurde und wie unsere Website genutzt wurde. Diese Daten verwenden wir unter anderem zur Optimierung unserer Website durch Auswertung der von uns durchgeführten Kampagnen.",
                                    "toggle" => [
                                        "value" => "analytics",
                                        "enabled" => false,
                                        "readonly" => false
                                    ],
                                    "cookie_table" => $cookieTable
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);  ?>
    </script>