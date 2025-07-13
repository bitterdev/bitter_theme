<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Area\GlobalArea;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;
use Concrete\Core\Site\Service;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\View\View;

/** @var Page $c */
/** @var View $view */

$app = Application::getFacadeApplication();
/** @var Service $siteService */
$siteService = $app->make(Service::class);
/** @var Request $r */
$r = $app->make(Request::class);
$site = $siteService->getSite();
$config = $site->getConfigRepository();
?>

    <footer <?php if ($config->get("bitter_theme.enable_extended_footer")) { echo "class=\"extended\""; } ?>>
        <?php if ($config->get("bitter_theme.enable_extended_footer")) { ?>
            <div class="extended-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <?php
                            $a = new GlobalArea('Footer Column 1');
                            $a->display();
                            ?>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <?php
                            $a = new GlobalArea('Footer Column 2');
                            $a->display();
                            ?>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <?php
                            $a = new GlobalArea('Footer Column 3');
                            $a->display();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="copyrights">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <?php if ($r->server->has("SERVER_NAME") &&
                            !str_contains(strtolower($r->server->get("SERVER_NAME")), "bitter.de")) {
                            $a = new GlobalArea('Footer Copyright (' . $r->server->get("SERVER_NAME") . ')');
                            $a->display();
                        } else {
                            $a = new GlobalArea('Footer Copyright');
                            $a->display();
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

<?php $this->inc("elements/footer_bottom.php"); ?>