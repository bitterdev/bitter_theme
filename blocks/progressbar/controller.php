<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme\Block\Progressbar;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Error\ErrorList\ErrorList;

class Controller extends BlockController
{

    protected $btTable = 'btProgressbar';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockOutputLifetime = 300;

    public function getBlockTypeDescription()
    {
        return t("Add progress bars to your site.");
    }

    public function getBlockTypeName()
    {
        return t("Progressbar");
    }

    public function getSearchableContent()
    {
        return sprintf("%s %s", $this->get("label"), $this->get("value"));
    }

    public function add()
    {
        $this->set("label", "");
        $this->set("value", "");
        $this->set("duration", 300);
    }

    public function validate($args)
    {
        $errorList = new ErrorList();

        if (!is_numeric($args["duration"]) || intval($args["duration"]) < 0) {
            $errorList->add(t('You must specify a valid animation duration.'));
        }

        if ($args["label"] == "") {
            $errorList->add(t('You must specify a text for the label.'));
        }

        if (intval($args["value"]) < 0 || intval($args["value"]) > 100) {
            $errorList->add(t('You must specify a valid value between 0 and 100.'));
        }

        return $errorList;
    }

}
