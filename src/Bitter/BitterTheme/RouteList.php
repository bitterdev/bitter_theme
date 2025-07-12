<?php

/**
 * @project:   BitterTheme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 */

namespace Bitter\BitterTheme;

use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        $router
            ->buildGroup()
            ->setNamespace('Concrete\Package\BitterTheme\Controller\Dialog\Support')
            ->setPrefix('/ccm/system/dialogs/bitter_theme')
            ->routes('dialogs/support.php', 'bitter_theme');
    }
}