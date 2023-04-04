<?php
/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Utility\Service\Identifier;

/** @var array $items */

$c = Page::getCurrentPage();

$app = Application::getFacadeApplication();
/** @var Identifier $identifier */
$identifier = $app->make(Identifier::class);
$isFirstItem = true;
?>

<?php if ($c instanceof Page && $c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Accordion is disabled in edit mode.') ?>
    </div>
<?php } else { ?>
    <?php foreach ($items as $item) { ?>
        <?php $panelId = "ccm-panel-" . $identifier->getString(); ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $panelId; ?>">
                        <?php echo $item["title"]; ?>
                    </a>
                </h4>
            </div>

            <div id="<?php echo $panelId; ?>" class="panel-collapse collapse <?php echo $isFirstItem ? " in" : "";?>">
                <div class="panel-body">
                    <?php echo $item["body"]; ?>
                </div>
            </div>
        </div>

        <?php $isFirstItem = false; ?>
    <?php } ?>
<?php } ?>