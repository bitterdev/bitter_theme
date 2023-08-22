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
use Concrete\Core\Html\Service\Navigation;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;

/** @var Controller $controller */

$c = Page::getCurrentPage();
$app = Application::getFacadeApplication();
/** @var Token $token */
$token = $app->make(Token::class);
/** @var Navigation $navigation */
$navigation = $app->make(Navigation::class);

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

echo '<nav class="legal">';
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

echo '<li>'; //opens a nav item
echo '<a href="#" data-cc="c-settings">' . t("Manage cookie settings") . '</a>';
echo '</li>';

echo '<li>'; //opens a nav item
echo $navigation->getLogInOutLink();
echo '</li>';

echo '</ul>';
echo '</nav>';
