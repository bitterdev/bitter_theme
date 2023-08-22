<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Area\GlobalArea;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Entity\File\File as FileEntity;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\File;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\View\View;
use Concrete\Package\BitterTheme\Controller as PackageController;

/** @var Page $c */
/** @var View $this */

$app = Application::getFacadeApplication();
/** @var Repository $config */
$config = $app->make(Repository::class);
/** @var PackageService $packageService */
$packageService = $app->make(PackageService::class);
/** @var PackageController $pkg */
$pkg = $packageService->getByHandle("bitter_theme")->getController();

$homePage = Page::getByID(Page::getHomePageID(Page::getCurrentPage()));

?>

<?php /** @noinspection PhpUnhandledExceptionInspection */
$this->inc("elements/header_top.php"); ?>


    <header>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="d-xs-block d-sm-none">
                        <div class="navbar navbar-light bg-light">
                            <a class="navbar-brand" href="<?php echo Url::to($homePage); ?>" title="<?php echo h($homePage->getCollectionName()); ?>">
                                <?php
                                $logoUrl = $pkg->getRelativePath() . "/images/default_logo_small.svg";

                                $logoFileId = (int)$config->get("bitter_theme.small_logo_file_id", 0);
                                $logoFile = File::getByID($logoFileId);

                                if ($logoFile instanceof FileEntity) {
                                    $logoVersion = $logoFile->getApprovedVersion();
                                    if ($logoVersion instanceof Version) {
                                        $logoUrl = $logoVersion->getRelativePath();
                                    }
                                }
                                ?>

                                <img src="<?php echo h($logoUrl); ?>" alt="<?php echo h(t("Home")); ?>"/>
                            </a>

                            <nav id="mobile-nav"></nav>

                            <a href="tel:+498007236913" class="call-us">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                    <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/>
                                </svg>

                                Jetzt anrufen
                            </a>

                            <a href="#mobile-nav">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                    <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="d-none d-sm-block">
                        <div id="logo">
                            <a href="<?php echo Url::to($homePage); ?>" title="<?php echo h($homePage->getCollectionName()); ?>">
                                <a href="<?php echo Url::to($homePage); ?>">
                                    <?php
                                    $logoUrl = $pkg->getRelativePath() . "/images/default_logo.svg";

                                    $logoFileId = (int)$config->get("bitter_theme.regular_logo_file_id", 0);
                                    $logoFile = File::getByID($logoFileId);

                                    if ($logoFile instanceof FileEntity) {
                                        $logoVersion = $logoFile->getApprovedVersion();
                                        if ($logoVersion instanceof Version) {
                                            $logoUrl = $logoVersion->getRelativePath();
                                        }
                                    }
                                    ?>

                                    <img src="<?php echo h($logoUrl); ?>" alt="<?php echo h(t("Home")); ?>"/>
                                </a>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav id="desktop-nav" class="d-none d-sm-block">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <?php
                        $a = new GlobalArea('Header Navigation');
                        $a->display();
                        ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!--
    <div class="col-sm-6">
        <?php
        $a = new GlobalArea('Header Language Switcher');
        $a->display();
        ?>
    </div>
    -->


<?php if ($c instanceof Page && !($c->getCurrentPage()->getCollectionId() == Page::getHomePageID() || $c->getAttribute("exclude_breadcrumb_nav"))) { ?>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php
                    $a = new GlobalArea('Breadcrumb');
                    $a->display($c);
                    ?>
                </div>
            </div>
        </div>
    </section>
<?php } ?>