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
        <div class="container hidden-xs">
            <div class="row">
                <div class="col-sm-6">
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
                </div>

                <div class="col-sm-6">
                    <?php
                    $a = new GlobalArea('Header Language Switcher');
                    $a->display();
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $a = new GlobalArea('Header Navigation');
                    $a->display();
                    ?>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-icon-top navbar-default visible-xs">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed">
                    <span class="sr-only">
                        <?php echo t("Toggle Navigation"); ?>
                    </span>

                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>


                    <a href="<?php echo Url::to($homePage); ?>" class="navbar-brand">
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
                </div>
            </div>
        </nav>
    </header>

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