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
    protected $pkgVersion = '3.1.2';
    protected $appVersionRequired = '9.2.0';
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
        if (file_exists($this->getPackagePath() . '/vendor/autoload.php')) {
            require_once($this->getPackagePath() . '/vendor/autoload.php');
        }

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

    public function install()
    {
        /** @var ServiceProvider $serviceProvider */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
    }
}
