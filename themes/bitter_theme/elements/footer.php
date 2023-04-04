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
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\View\View;

/** @var Page $c */
/** @var View $view */

$app = Application::getFacadeApplication();
/** @var Repository $config */
$config = $app->make(Repository::class);
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
                        <?php
                        $a = new GlobalArea('Footer Copyright');
                        $a->display();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

<?php $this->inc("elements/footer_bottom.php"); ?>