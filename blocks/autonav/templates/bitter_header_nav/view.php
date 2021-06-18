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
use Concrete\Core\Page\Page;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\User;
use Concrete\Core\Validation\CSRF\Token;

/** @var Controller $controller */

$c = Page::getCurrentPage();
$app = Application::getFacadeApplication();
/** @var Token $token */
$token = $app->make(Token::class);

if ($c instanceof Page && $c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Empty Auto-Nav Block.') ?>
    </div>
<?php } else {
    $navItems = $controller->getNavItems();

    foreach ($navItems as $ni) {
        $classes = [];

        if ($ni->isCurrent) {
            $classes[] = 'nav-selected';
        }
        if ($ni->hasSubmenu) {
            $classes[] = 'has-suvnav';
        }

        if ($ni->inPath) {
            $classes[] = 'nav-path-selected';
        }

        $ni->classes = implode(" ", $classes);
    }

    echo '<nav class="main">';
    echo '<ul class="nav">';

    foreach ($navItems as $ni) {
        echo '<li class="' . $ni->classes . '">';
        echo '<a href="' . $ni->url . '" target="' . $ni->target . '" class="' . $ni->classes . '" title="' . h($ni->name) . '">' . $ni->name . '</a>';

        if ($ni->hasSubmenu) {
            echo '<ul>';
        } else {
            echo '</li>';

            echo str_repeat('</ul></li>', $ni->subDepth);
        }
    }

    // append account menu
    $user = new User();

    if ($user->isRegistered()) {
        $account = Page::getByPath('/account');

        if ($account instanceof Page && !$account->isError()) {
            $isNavPathSelected = false;
            $accountPages = [];

            foreach ($account->getCollectionChildrenArray(true) as $accountPageId) {
                $accountPage = Page::getByID($accountPageId, 'ACTIVE');
                $permissionChecker = new Checker($accountPage);
                /** @noinspection PhpUndefinedMethodInspection */
                if ($permissionChecker->canRead() && (!$accountPage->getAttribute('exclude_nav'))) {

                    if ($accountPage->getCollectionID() == $c->getCollectionID()) {
                        $isNavPathSelected = true;
                    }

                    $accountPages[] = $accountPage;
                }
            }

            echo '<li class="' . ($isNavPathSelected ? "nav-path-selected nav-selected" : "") . '">'; //opens a nav item
            echo '<a href="' . Url::to($account) . '" class="' . ($isNavPathSelected ? "nav-path-selected nav-selected" : "") . '" title="' . h($account->getCollectionName()) . '">' . h($account->getCollectionName()) . '</a>';
            echo '<ul>';
            ?>

            <?php foreach ($accountPages as $cc) { ?>
                <li class="<?php echo $c->getCollectionID() == $cc->getCollectionID() ? "nav-path-selected nav-selected" : "";?>">
                    <a href="<?php echo Url::to($cc) ?>" class="<?php echo $c->getCollectionID() == $cc->getCollectionID() ? "nav-path-selected nav-selected" : "";?>">
                        <?php echo t($cc->getCollectionName()) ?>
                    </a>
                </li>
            <?php } ?>

            <li>
                <a href="<?php echo Url::to('/login', 'do_logout', $token->generate('do_logout')) ?>">
                    <?php echo t('Log out') ?>
                </a>
            </li>

        <?php }
        echo '</ul>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</nav>';

}