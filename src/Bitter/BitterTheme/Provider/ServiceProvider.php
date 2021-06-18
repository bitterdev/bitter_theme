<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\BitterTheme\Provider;

use Concrete\Core\Application\Application;
use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Html\Service\Navigation;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Page;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Concrete\Core\Page\Theme\ThemeRouteCollection;

class ServiceProvider extends Provider
{
    protected $eventDispatcher;
    protected $responseFactory;
    protected $navigationHelper;
    protected $themeRouteCollection;
    protected $config;

    public function __construct(
        Application $app,
        EventDispatcherInterface $eventDispatcher,
        ResponseFactory $responseFactory,
        Navigation $navigationHelper,
        ThemeRouteCollection $themeRouteCollection,
        Repository $config
    )
    {
        parent::__construct($app);

        $this->eventDispatcher = $eventDispatcher;
        $this->responseFactory = $responseFactory;
        $this->navigationHelper = $navigationHelper;
        $this->themeRouteCollection = $themeRouteCollection;
        $this->config = $config;
    }

    public function register()
    {
        $this->registerAssets();
        $this->registerPageSelectorRedirect();
        $this->registerThemePaths();
        $this->disableAccountMenu();
    }


    private function disableAccountMenu()
    {
        $this->config->set('user.display_account_menu', false);
    }

    private function registerThemePaths()
    {
        if (!$this->themeRouteCollection->getThemeByRoute('/account')) {
            $this->themeRouteCollection->setThemeByRoute('/account', 'bitter_theme');
        }

        if (!$this->themeRouteCollection->getThemeByRoute('/account/*')) {
            $this->themeRouteCollection->setThemeByRoute('/account/*', 'bitter_theme');
        }

        $this->themeRouteCollection->setThemeByRoute('/register', 'bitter_theme');
        $this->themeRouteCollection->setThemeByRoute('/login', 'bitter_theme');
    }

    private function registerAssets()
    {
        $assetList = AssetList::getInstance();

        $assetList->register('javascript', 'bootstrap', "assets/bootstrap/js/bootstrap.min.js", ["version" => "3.3.7", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");
        $assetList->register('css', 'bootstrap', "assets/bootstrap/css/bootstrap.min.css", ["version" => "3.3.7", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");

        $assetList->registerGroup(
            "bootstrap",

            [
                ['javascript', 'bootstrap'],
                ['css', 'bootstrap']
            ]
        );

        $assetList->register('javascript', 'mmenu-light', "assets/mmenu-light/mmenu-light.js", ["version" => "4.0.3", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'mmenu-light', "assets/mmenu-light/mmenu-light.css", ["version" => "4.0.3", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");

        $assetList->registerGroup(
            "mmenu-light",

            [
                ['javascript', 'mmenu-light'],
                ['css', 'mmenu-light']
            ]
        );

        $assetList->register('javascript', 'slick', "assets/slick/slick.min.js", ["version" => "1.8.1", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'slick', "assets/slick/slick.css", ["version" => "1.8.1", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'slick-theme', "assets/slick/slick-theme.css", ["version" => "1.8.1", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");

        $assetList->registerGroup(
            "slick",

            [
                ['javascript', 'slick'],
                ['css', 'slick'],
                ['css', 'slick-theme']
            ]
        );
    }

    private function registerPageSelectorRedirect()
    {
        $this->eventDispatcher->addListener('on_before_render', function () {
            $page = Page::getCurrentPage();

            if ($page instanceof Page && !$page->isError()) {
                $targetPageId = (int)$page->getAttribute('page_selector_redirect');

                if ($targetPageId > 0) {
                    $targetPage = Page::getByID($targetPageId);

                    if ($targetPage instanceof Page && !$targetPage->isError()) {
                        if ($targetPage->isExternalLink()) {
                            $targetPageUrl = $targetPage->getCollectionPointerExternalLink();
                        } else {
                            /** @noinspection PhpParamsInspection */
                            $targetPageUrl = $this->navigationHelper->getLinkToCollection($targetPage);
                        }

                        $this->responseFactory->redirect($targetPageUrl, Response::HTTP_TEMPORARY_REDIRECT)->send();
                        $this->app->shutdown();
                    }
                }
            }
        });
    }
}