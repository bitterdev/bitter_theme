<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 */

namespace Concrete\Package\BitterTheme\Controller\SinglePage\Dashboard\BitterTheme;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Form\Service\Validation;
use Concrete\Core\Page\Controller\DashboardSitePageController;

class Settings extends DashboardSitePageController
{
    /** @var Repository */
    protected $config;
    /** @var Validation */
    protected $formValidator;
    /** @var Repository */
    protected $globalConfig;

    public function on_start()
    {
        parent::on_start();
        $this->config = $this->getSite()->getConfigRepository();
        $this->globalConfig = $this->app->make(Repository::class);
        $this->formValidator = $this->app->make(Validation::class);
    }

    public function view()
    {
        if ($this->request->getMethod() === "POST") {
            $this->formValidator->setData($this->request->request->all());
            $this->formValidator->addRequiredToken("update_settings");

            if ($this->formValidator->test()) {
                $this->config->save("bitter_theme.regular_logo_file_id", (int)$this->request->request->get("regularLogoFileId"));
                $this->config->save("bitter_theme.small_logo_file_id", (int)$this->request->request->get("smallLogoFileId"));
                $this->config->save("bitter_theme.privacy_page_id", (int)$this->request->request->get("privacyPageId"));
                $this->config->save("bitter_theme.phone_number", (string)$this->request->request->get("phoneNumber"));
                $this->config->save("bitter_theme.enable_extended_footer", (bool)$this->request->request->has("enableExtendedFooter"));
                $this->globalConfig->save("bitter_theme.enable_cookie_banner", (bool)$this->request->request->has("enableCookieBanner"));
                $this->globalConfig->save("bitter_theme.enable_gdpr_maps", (bool)$this->request->request->has("enableGdprMaps"));
                $this->globalConfig->save("bitter_theme.enable_custom_login_page", (bool)$this->request->request->has("enableCustomLoginPage"));

                if (!$this->error->has()) {
                    $this->set("success", t("The settings has been successfully updated."));
                }
            } else {
                /** @var ErrorList $errorList */
                $errorList = $this->formValidator->getError();

                foreach ($errorList->getList() as $error) {
                    $this->error->add($error);
                }
            }
        }

        $this->set("regularLogoFileId", (int)$this->config->get("bitter_theme.regular_logo_file_id"));
        $this->set("smallLogoFileId", (int)$this->config->get("bitter_theme.small_logo_file_id"));
        $this->set("privacyPageId", (int)$this->config->get("bitter_theme.privacy_page_id"));
        $this->set("enableExtendedFooter", (bool)$this->config->get("bitter_theme.enable_extended_footer"));
        $this->set("phoneNumber", (string)$this->config->get("bitter_theme.phone_number"));
        $this->set("enableCookieBanner", (bool)$this->globalConfig->get("bitter_theme.enable_cookie_banner", true));
        $this->set("enableGdprMaps", (bool)$this->globalConfig->get("bitter_theme.enable_gdpr_maps", true));
        $this->set("enableCustomLoginPage", (bool)$this->globalConfig->get("bitter_theme.enable_custom_login_page", true));
    }
}