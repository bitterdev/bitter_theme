<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

namespace Concrete\Package\BitterTheme\Theme\BitterTheme;

use Concrete\Core\Feature\Features;
use Concrete\Core\Page\Theme\BedrockThemeTrait;
use Concrete\Core\Page\Theme\Color\Color;
use Concrete\Core\Page\Theme\Color\ColorCollection;
use Concrete\Core\Page\Theme\Documentation\AtomikDocumentationProvider;
use Concrete\Core\Page\Theme\Documentation\DocumentationProviderInterface;
use Concrete\Core\Page\Theme\Theme;

class PageTheme extends Theme
{
    use BedrockThemeTrait {
        getColorCollection as getBedrockColorCollection;
    }

    protected $pThemeGridFrameworkHandle = 'bootstrap5';

    public function getThemeName()
    {
        return t('Bitter Theme');
    }

    public function getThemeDescription()
    {
        return t('A Concrete CMS theme built for version 9.');
    }

    public function getThemeSupportedFeatures()
    {
        return [
            Features::ACCOUNT,
            Features::ACCORDIONS,
            Features::DESKTOP,
            Features::BASICS,
            Features::TYPOGRAPHY,
            Features::DOCUMENTS,
            Features::CONVERSATIONS,
            Features::FAQ,
            Features::PROFILE,
            Features::NAVIGATION,
            Features::IMAGERY,
            Features::FORMS,
            Features::SEARCH,
            Features::STAGING,
            Features::TESTIMONIALS,
            Features::TAXONOMY,
        ];
    }

    public function registerAssets()
    {
        $this->requireAsset('javascript', 'jquery');
        $this->requireAsset('vue');
        $this->requireAsset('moment');
        $this->requireAsset('bootstrap');
        $this->requireAsset('mmenu-light');
        $this->requireAsset('slick');
        $this->requireAsset('toastify');
        //$this->requireAsset('cookieconsent');
        $this->requireAsset('iframemanager');
        $this->requireAsset('css', 'font-awesome');
        $this->requireAsset('javascript', 'respond');
        $this->requireAsset('javascript', 'html5-shiv');
        $this->requireAsset('core/lightbox');
        $this->requireAsset('jquery/ui');
        $this->requireAsset('javascript', 'macy');
        $this->requireAsset('javascript', 'underscore');
        $this->requireAsset('photoswipe');
        $this->requireAsset('photoswipe/default-skin');
    }

    /**
     * @return array
     */
    public function getThemeResponsiveImageMap()
    {
        return [
            'xl' => '1200px',
            'lg' => '992px',
            'md' => '768px',
            'sm' => '576px',
            'xs' => '0',
        ];
    }

    public function getThemeAreaLayoutPresets()
    {
        $presets = [
            [
                'handle' => 'left_sidebar',
                'name' => 'Left Sidebar',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-md-4"></div>',
                    '<div class="col-md-8"></div>',
                ],
            ],
            [
                'handle' => 'right_sidebar',
                'name' => 'Right Sidebar',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-md-8"></div>',
                    '<div class="col-md-4"></div>',
                ],
            ],
            [
                'handle' => 'split',
                'name' => '2 Columns 50/50',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-md-6"></div>',
                    '<div class="col-md-6"></div>',
                ],
            ],
            [
                'handle' => 'three_columns',
                'name' => '3 Columns',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-md-4"></div>',
                    '<div class="col-md-4"></div>',
                    '<div class="col-md-4"></div>',
                ],
            ],
            [
                'handle' => 'four_columns',
                'name' => '4 Columns',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-md-3"></div>',
                    '<div class="col-md-3"></div>',
                    '<div class="col-md-3"></div>',
                    '<div class="col-md-3"></div>',
                ],
            ],
            [
                'handle' => 'six_columns',
                'name' => '6 Columns',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-md-2"></div>',
                    '<div class="col-md-2"></div>',
                    '<div class="col-md-2"></div>',
                    '<div class="col-md-2"></div>',
                    '<div class="col-md-2"></div>',
                    '<div class="col-md-2"></div>',
                ],
            ],
        ];

        return $presets;
    }

    /**
     * @return array
     */
    public function getThemeEditorClasses()
    {
        return [
            [
                'title' => t('Display 1'),
                'element' => array('h1','p','div'),
                'attributes' => array('class' => 'display-1')
            ],
            [
                'title' => t('Display 2'),
                'element' => array('h2','p','div'),
                'attributes' => array('class' => 'display-2')
            ],
            [
                'title' => t('Display 3'),
                'element' => array('h3','p','div'),
                'attributes' => array('class' => 'display-3')
            ],
            [
                'title' => t('Display 4'),
                'element' => array('h4','p','div'),
                'attributes' => array('class' => 'display-4')
            ],
            [
                'title' => t('Display 5'),
                'element' => array('h5','p','div'),
                'attributes' => array('class' => 'display-5')
            ],
            [
                'title' => t('Display 6'),
                'element' => array('h6','p','div'),
                'attributes' => array('class' => 'display-6')
            ],
            [
                'title' => t('Lead'),
                'element' => array('p'),
                'attributes' => array('class' => 'lead')
            ],
            [
                'title' => t('Basic Table'),
                'element' => array('table'),
                'attributes' => array('class' => 'table')
            ],
            [
                'title' => t('Striped Table'),
                'element' => array('table'),
                'attributes' => array('class' => 'table table-striped')
            ],
        ];
    }

}
