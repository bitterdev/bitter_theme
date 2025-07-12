<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied.');

use Concrete\Core\Attribute\Key\Key;
use Concrete\Core\Authentication\AuthenticationType;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\File\File;
use Concrete\Core\Form\Service\Form;
use /** @noinspection PhpDeprecationInspection */
    Concrete\Core\Form\Service\Widget\Attribute;
use Concrete\Core\Html\Service\Navigation;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;
use Concrete\Core\Site\Service;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\User;
use Concrete\Core\View\View;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Entity\File\File as FileEntity;
use Concrete\Core\Entity\File\Version;
use Concrete\Package\BitterTheme\Controller as PackageController;
use Concrete\Core\Package\PackageService;

/** @var User $user */
/** @var string $authType */
/* @var Key[] $required_attributes */
/** @var View $this */
/** @var array|ErrorList $error */
/** @var string $success */
/** @var string $message */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var Navigation $navHelper */
$navHelper = $app->make(Navigation::class);
/** @var Request $request */
$request = $app->make(Request::class);
/** @var Repository $config */
$config = $app->make(Repository::class);
/** @var PackageService $packageService */
$packageService = $app->make(PackageService::class);
/** @var PackageController $pkg */
$pkg = $packageService->getByHandle("bitter_theme")->getController();
/** @var Service $siteService */
$siteService = $app->make(Service::class);
$site = $siteService->getSite();
$siteConfig = $site->getConfigRepository();

if (isset($authType) && $authType) {
    $active = $authType;
    $activeAuths = [$authType];
} else {
    $active = null;
    $activeAuths = AuthenticationType::getList(true, true);
}

if (!isset($authTypeElement)) {
    $authTypeElement = null;
}

if (!isset($authTypeParams)) {
    $authTypeParams = null;
}

$hasRequiredAttributes = (isset($required_attributes) && count($required_attributes));

// See if we have a user object and if that user is registered
$loggedIn = !$hasRequiredAttributes && isset($user) && $user->isRegistered();
/** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/header_top.php');

?>

    <main class="centered">
        <div>
            <div class="col-sm-12">
                <?php
                $logoUrl = $pkg->getRelativePath() . "/images/default_logo.svg";

                $logoFileId = (int)$siteConfig->get("bitter_theme.regular_logo_file_id", 0);
                $logoFile = File::getByID($logoFileId);

                if ($logoFile instanceof FileEntity) {
                    $logoVersion = $logoFile->getApprovedVersion();
                    if ($logoVersion instanceof Version) {
                        $logoUrl = $logoVersion->getRelativePath();
                    }
                }
                ?>

                <img src="<?php echo h($logoUrl); ?>" alt="<?php echo h(t("Home")); ?>"/>
            </div>

            <div class="col-sm-12">
                <?php
                /** @noinspection PhpUnhandledExceptionInspection */
                View::element('system_errors', [
                    'format' => 'block',
                    'error' => isset($error) ? $error : null,
                    'success' => isset($success) ? $success : null,
                    'message' => isset($message) ? $message : null,
                ]); ?>
            </div>

            <?php if ($hasRequiredAttributes) { ?>
                <?php /** @noinspection PhpDeprecationInspection */
                $attribute_helper = new Attribute(); ?>

                <div class="attribute-mode">
                    <form action="<?php echo View::action('fill_attributes'); ?>" method="post">
                        <div data-handle="required_attributes"
                             class="authentication-type authentication-type-required-attributes">

                            <div class="ccm-required-attribute-form">
                                <?php foreach ($required_attributes as $key) {
                                    echo $attribute_helper->display($key, true);
                                } ?>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary pull-right">
                                    <?php echo t('Submit'); ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } else { ?>
                <div class="col-sm-12">
                    <?php if ($loggedIn) { ?>
                        <div class="text-center">
                            <h2>
                                <?php echo t('You are already logged in.'); ?>
                            </h2>

                            <?php echo $navHelper->getLogInOutLink(); ?>
                        </div>

                    <?php } else { ?>
                        <?php $i = 0; ?>

                        <?php foreach ($activeAuths as $auth) { ?>
                            <div data-handle="<?php echo $auth->getAuthenticationTypeHandle(); ?>"
                                 class="authentication-type authentication-type-<?php echo $auth->getAuthenticationTypeHandle(); ?>">
                                <?php $auth->renderForm($authTypeElement ?: 'form', $authTypeParams ?: []); ?>
                            </div>

                            <?php if ($i == 0 && count($activeAuths) > 1 && $config->get('concrete.user.registration.enabled')) { ?>
                                <div class="text-center" style="color: #ffffff; margin-bottom: 15px;">
                                    <?php echo t('or'); ?>
                                </div>
                            <?php } elseif ($i == 0 && count($activeAuths) > 1) { ?>
                                <?php echo '<hr>'; ?>
                            <?php } ?>

                            <?php ++$i; ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
    </main>

<?php
/** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/footer_bottom.php');
