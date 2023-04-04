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

/** @var string $innerContent */
/** @var View $this */

$c = Page::getCurrentPage();

/** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/header.php');
?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php
                    /** @noinspection PhpUnhandledExceptionInspection */
                    View::element('system_errors', [
                        'format' => 'block',
                        'error' => isset($error) ? $error : null,
                        'success' => isset($success) ? $success : null,
                        'message' => isset($message) ? $message : null,
                    ]);

                    echo $innerContent;
                    ?>
                </div>
            </div>
        </div>
    </main>

<?php /** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/footer.php');
