<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Routing\Router;

/** @var Router $router */

$router
    ->buildGroup()
    ->setPrefix("/bitter_theme/api")
    ->setNamespace("Bitter\BitterTheme\Controller")
    ->routes(function($groupRouter) {
        $groupRouter->get('/hide_reminder', 'Api::hideReminder');
        $groupRouter->get('/hide_did_you_know', 'Api::hideDidYouKnow');
        $groupRouter->get('/hide_license_check', 'Api::hideLicenseCheck');
    });