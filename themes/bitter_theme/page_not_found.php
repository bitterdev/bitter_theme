<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Page\Page;
use Concrete\Core\View\View;

/** @var Page $c */
/** @var View $this */
?>

<?php /** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/header.php'); ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h1>
                        <?php echo t("Page not found"); ?>
                    </h1>

                    <p>
                        <?php echo t('The requested page could not be found.') ?>
                    </p>
                </div>
            </div>
        </div>
    </main>

<?php /** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/footer.php'); ?>