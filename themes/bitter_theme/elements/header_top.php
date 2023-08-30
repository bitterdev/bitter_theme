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
            ],
            "cookieDisclosure" => [
                "language" => $language,
                "languages" => [
                    $language => [
                        "consent_modal" => [
                            "title" => t("We use cookies!"),
                            "description" => t("We use multiple cookies on our website for technical, marketing and for analysis purposes; In principle, you can also visit our website without setting cookies. The technically necessary cookies are excluded from this. You have a right of withdrawal at any time. By clicking on the accept all button, you agree that we set the additional cookies for marketing and analysis purposes."),
                            "primary_btn" => [
                                "text" => t("Accept all"),
                                "role" => "accept_all"
                            ],
                            "secondary_btn" => [
                                "text" => t("Manage cookie settings"),
                                "role" => "settings"
                            ],
                        ],
                        "settings_modal" => [
                            "title" => t("Cookie preferences"),
                            "save_settings_btn" => t('Save settings'),
                            "accept_all_btn" => t('Accept all'),
                            "reject_all_btn" => t('Reject all'),
                            "close_btn_label" => t('Close'),
                            "cookie_table_headers" => [
                                [
                                    "col1" => t("Name")
                                ],
                                [
                                    "col2" => t("Domain")
                                ],
                                [
                                    "col3" => t("Expiration")
                                ]
                            ],
                            "blocks" => [
                                [
                                    "description" => t("Further information you can find in our <a class=\"cc-link\" href=\"{0}\">privacy policy</a>.")
                                ],
                                [
                                    "title" => t('Strictly necessary cookies'),
                                    "description" => t('These cookies are essential for the proper functioning of our website. Without these cookies, the website would not work properly'),
                                    "toggle" => [
                                        "value" => "necessary",
                                        "enabled" => true,
                                        "readonly" => true
                                    ]
                                ],
                                [
                                    "title" =>t( "Analytics & Marketing Cookies"),
                                    "description" => t("These cookies allow the website to remember the choices you have made in the past."),
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