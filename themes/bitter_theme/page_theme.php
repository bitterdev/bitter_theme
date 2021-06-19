<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme\Theme\BitterTheme;

use Concrete\Core\Page\Theme\Theme;

class PageTheme extends Theme
{
    protected $pThemeGridFrameworkHandle = 'bootstrap3';

    public function registerAssets()
    {
        $this->requireAsset('javascript', 'jquery');
        $this->requireAsset('bootstrap');
        $this->requireAsset('mmenu-light');
        $this->requireAsset('slick');
        $this->requireAsset('css', 'font-awesome');
        $this->requireAsset('javascript', 'respond');
        $this->requireAsset('javascript', 'html5-shiv');
        $this->requireAsset('javascript', 'particles');
        $this->providesAsset('javascript', 'bootstrap/*');
        $this->requireAsset('core/lightbox');
        $this->requireAsset('jquery/ui');
        $this->requireAsset('javascript', 'macy');
        $this->requireAsset('photoswipe');
        $this->requireAsset('photoswipe/default-skin');
    }
}
