<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Block\Autonav\Controller;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Html\Service\Seo;
use Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;

$c = Page::getCurrentPage();

$app = Application::getFacadeApplication();
/** @var Seo $seoService */
$seoService = $app->make('helper/seo');
/** @var Repository $config */
$config = $app->make(Repository::class);


/** @var Controller $controller */
$navItems = $controller->getNavItems(true);

if ($c->getController() instanceof AccountPageController) {
    // this is a account page
    $parentPage = Page::getByID($c->getCollectionParentID());

    if ($parentPage->getController() instanceof AccountPageController) {
        $accountPage = new stdClass();
        $accountPage->name = $parentPage->getCollectionName();
        $accountPage->url = Url::to($parentPage);
        $navItems[] = $accountPage;
    }

    $accountPage = new stdClass();
    $accountPage->name = $c->getCollectionName();
    $accountPage->isCurrent = true;
    $navItems[] = $accountPage;
}

foreach ($c->getBlocks() as $block) {
    if ($block->getBlockTypeHandle() === "product_details") {
        // this is a product detail page
        array_pop($navItems);

        // extract product name from SEO service
        $productName = trim(explode($config->get('concrete.seo.title_segment_separator'), $seoService->getTitle())[0]);

        $productDetailPage = new stdClass();
        $productDetailPage->name = $productName;
        $productDetailPage->isCurrent = true;
        $navItems[] = $productDetailPage;
        break;
    }
}

$navItems[0]->name = "<i class=\"fa fa-home\"></i>";

if (count($navItems) > 0) {
    echo '<nav role="navigation" aria-label="breadcrumb">';
    echo '<ol class="breadcrumb">';

    foreach ($navItems as $ni) {
        if ($ni->isCurrent) {
            echo '<li class="active">' . $ni->name . '</li>';
        } else {
            echo '<li><a href="' . $ni->url . '" target="' . $ni->target . '">' . $ni->name . '</a></li>';
        }
    }

    echo '</ol>';
    echo '</nav>';
} elseif (is_object($c) && $c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Empty Auto-Nav Block.') ?>
    </div>
<?php }
