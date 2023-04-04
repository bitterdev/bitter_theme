<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Area\Area;
use Concrete\Core\Area\GlobalArea;
use Concrete\Core\Page\Page;
use Concrete\Core\View\View;

/** @var Page $c */
/** @var View $this */

?>

<?php /** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/header.php'); ?>

    <main>
        <?php
        $a = new Area('Main');
        $a->enableGridContainer();
        $a->display($c);
        ?>

        <?php
        $extraAreas = (int)$c->getAttribute("main_area_number");
        if ($extraAreas > 0) {
            for ($i = 1; $i <= (int)$c->getAttribute("main_area_number"); $i++) {
                $a = new Area('Custom Area ' . $i);
                $a->enableGridContainer();
                $a->display($c);
            }
        }
        ?>
    </main>

<?php /** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/footer.php'); ?>