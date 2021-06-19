<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\BitterTheme\Controller;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Http\Request;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Package\Package;
use Concrete\Core\Package\PackageService;

class Api implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    protected $responseFactory;
    /** @var Package|PackageEntity */
    protected $pkg;
    protected $request;

    public function __construct(
        ResponseFactory $responseFactory,
        PackageService $packageService,
        Request $request
    )
    {
        $this->responseFactory = $responseFactory;
        $this->pkg = $packageService->getByHandle("bitter_theme");
        $this->request = $request;
    }

    public function hideReminder()
    {
        $this->pkg->getConfig()->save('reminder.hide', true);
        return $this->responseFactory->create("");
    }

    public function hideDidYouKnow()
    {
        $this->pkg->getConfig()->save('did_you_know.hide', true);
        return $this->responseFactory->create("");
    }

    public function hideLicenseCheck()
    {
        $this->pkg->getConfig()->save('license_check.hide', true);
        return $this->responseFactory->create("");
    }
}