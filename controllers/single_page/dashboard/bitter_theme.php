<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme\Controller\SinglePage\Dashboard;

use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Support\Facade\Url;

class BitterTheme extends DashboardPageController
{
    /** @var ResponseFactory */
    protected $responseFactory;

    public function on_start()
    {
        parent::on_start();
        $this->responseFactory = $this->app->make(ResponseFactory::class);
    }

    public function view()
    {
        return $this->responseFactory->redirect((string)Url::to("/dashboard/bitter_theme/settings"), Response::HTTP_TEMPORARY_REDIRECT);
    }
}
