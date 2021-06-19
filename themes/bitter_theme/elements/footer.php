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
use Concrete\Core\Page\Page;
use Concrete\Core\View\View;

/** @var Page $c */
/** @var View $view */

?>

    <footer>
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

<?php $this->inc("elements/footer_top.php"); ?>