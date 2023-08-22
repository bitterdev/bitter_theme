<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\BitterTheme\Provider;

use Bitter\BitterTheme\Backup\ContentImporter\Importer\Routine\ImportFileSetsRoutine;
use Bitter\BitterTheme\Backup\ContentImporter\Importer\Routine\ImportMultilingualContentRoutine;
use Bitter\BitterTheme\Backup\ContentImporter\ValueInspector\InspectionRoutine\FileSetRoutine;
use Bitter\BitterTheme\RouteList;
use Concrete\Core\Application\Application;
use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Backup\ContentImporter\Importer\Manager as ImporterManager;
use Concrete\Core\Backup\ContentImporter\ValueInspector\ValueInspector;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Html\Service\Navigation;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Page;
use Concrete\Core\Routing\Router;
use Concrete\Core\User\Group\Group;
use Concrete\Core\User\User;
use Gajus\Dindent\Indenter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Concrete\Core\Page\Theme\ThemeRouteCollection;
use Symfony\Component\EventDispatcher\GenericEvent;

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
        $this->initializeRoutes();
        $this->beautifyHtmlOutput();
        $this->addImporterRoutines();
    }

    private function addImporterRoutines()
    {
        $this->app->bindshared(
            'import/item/manager',
            function ($app) {
                $importer = $app->make(ImporterManager::class);
                // need to register this method before the core routines are registered
                $importer->registerImporterRoutine($this->app->make(ImportFileSetsRoutine::class));
                foreach($app->make('config')->get('app.importer_routines') as $routine) {
                    $importer->registerImporterRoutine($app->make($routine));
                }
                $importer->registerImporterRoutine($this->app->make(ImportMultilingualContentRoutine::class));
                return $importer;
            }
        );

        /** @var ValueInspector $valueInspector */
        $valueInspector = $this->app->make('import/value_inspector');
        $valueInspector->registerInspectionRoutine(new FileSetRoutine());
    }

    private function beautifyHtmlOutput()
    {
        $this->eventDispatcher->addListener('on_page_output', function ($event) {
            /** @var $event GenericEvent */
            $htmlCode = $event->getArgument('contents');

            $u = new User();

            $adminGroup = Group::getByName("Administrators");

            /** @var $c Page */
            $c = Page::getCurrentPage();

            if (!($u->isSuperUser() || (is_object($adminGroup) && $u->inGroup($adminGroup)) ||
                ($c instanceof Page && ($c->isEditMode())))) {
                $htmlBeautifier = new Indenter();
                $htmlCode = $htmlBeautifier->indent($htmlCode);
            }

            $event->setArgument('contents', $htmlCode);
        });
    }

    private function initializeRoutes()
    {
        /** @var Router $router */
        $router = $this->app->make("router");
        $list = new RouteList();
        $list->loadRoutes($router);
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

        $assetList->register('javascript', 'mmenu-light', "js/mmenu-light.js", ["version" => "4.0.3", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'mmenu-light', "css/mmenu-light.css", ["version" => "4.0.3", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");

        $assetList->registerGroup(
            "mmenu-light",

            [
                ['javascript', 'mmenu-light'],
                ['css', 'mmenu-light']
            ]
        );

        $assetList->register('javascript', 'slick', "js/slick.min.js", ["version" => "1.8.1", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'slick', "css/slick.css", ["version" => "1.8.1", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");
        $assetList->register('css', 'slick-theme', "css/slick-theme.css", ["version" => "1.8.1", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");

        $assetList->registerGroup(
            "slick",

            [
                ['javascript', 'slick'],
                ['css', 'slick'],
                ['css', 'slick-theme']
            ]
        );

        $assetList->register('javascript', 'macy', "js/macy.js", ["version" => "2.0.0", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");

        $assetList->register('javascript', 'photoswipe', "js/photoswipe.min.js", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'photoswipe', "css/photoswipe.css", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");

        $assetList->registerGroup(
            "photoswipe",

            [
                ['javascript', 'photoswipe'],
                ['css', 'photoswipe']
            ]
        );

        $assetList->register('css', 'photoswipe/default-skin', "css/default-skin.css", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");
        $assetList->register('javascript', 'photoswipe/default-skin', "js/photoswipe-ui-default.min.js", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");

        $assetList->registerGroup(
            "photoswipe/default-skin",

            [
                ['javascript', 'photoswipe/default-skin'],
                ['css', 'photoswipe/default-skin']
            ]
        );


        $assetList->register('javascript', 'toastify', "js/toastify.js", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'toastify', "css/toastify.css", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");

        $assetList->registerGroup(
            "toastify",
            [
                ['javascript', 'toastify'],
                ['css', 'toastify']
            ]
        );

        $assetList->register('javascript', 'iframemanager', "js/iframemanager.js", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'iframemanager', "css/iframemanager.css", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");

        $assetList->registerGroup(
            "iframemanager",
            [
                ['javascript', 'iframemanager'],
                ['css', 'iframemanager']
            ]
        );

        $assetList->register('javascript', 'cookieconsent', "js/cookieconsent.js", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_FOOTER], "bitter_theme");
        $assetList->register('css', 'cookieconsent', "css/cookieconsent.css", ["version" => "4.1.3", "position" => Asset::ASSET_POSITION_HEADER], "bitter_theme");

        $assetList->registerGroup(
            "cookieconsent",
            [
                ['javascript', 'cookieconsent'],
                ['css', 'cookieconsent']
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