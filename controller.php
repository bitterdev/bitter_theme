<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme;

use Bitter\BitterTheme\Provider\ServiceProvider;
use Concrete\Core\Package\Package;
use Concrete\Theme\Concrete\PageTheme;

class Controller extends Package
{
    protected $pkgHandle = 'bitter_theme';
    protected $pkgVersion = '2.0.1';
    protected $appVersionRequired = '8.5.4';
    protected $pkgAllowsFullContentSwap = true;
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/BitterTheme' => 'Bitter\BitterTheme',
    ];

    public function getPackageDescription()
    {
        return t('Powerful Theme for ConcreteCMS.');
    }

    public function getPackageName()
    {
        return t('Bitter Theme');
    }

    public function on_start()
    {
        /** @var ServiceProvider $serviceProvider */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function testForUninstall()
    {
        $pageTheme = PageTheme::getByHandle('elemental');
        if (is_object($pageTheme)) {
            $pageTheme->applyToSite();
        }
    }
}
