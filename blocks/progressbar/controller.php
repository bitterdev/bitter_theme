<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme\Block\Progressbar;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;

class Controller extends BlockController {

    public $helpers = array(
        'form',
    );

    public $btFieldsRequired = array(
        'isInlineLabel',
        'isThick',
        'hasAnimation',
        'animationDuration',
        'label',
        'value',
        'barColor',
        'labelColor',
        'backgroundColor'
    );

    protected $btExportFileColumns = array();
    protected $btTable = 'btProgressbar';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputLifetime = 300;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;

    public function getBlockTypeDescription() {
        return t("Allows you to add progress bars to your site.");
    }

    public function getBlockTypeName() {
        return t("Progressbar");
    }

    public function getSearchableContent() {
        return sprintf("%s %s", $this->label, $this->value);
    }

    public function view() {
        $this->requireAsset("javascript", "jquery");
    }


    /**
     * @param string $color
     *
     * @return boolean
     */
    public static function isValidColor($color) {
        $allColors = array('aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen', 'transparent');

        if (in_array(strtolower($color), $allColors)) {
            return true;
        } else if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return true;
        } else if (preg_match('/^[a-f0-9]{6}$/i', $color)) {
            return true;
        }

        return false;
    }

    public function validate($args) {
        $e = Core::make('helper/validation/error');

        if (!is_numeric($args["animationDuration"]) || intval($args["animationDuration"]) < 0) {
            $e->add(t('You must specify a valid animation duration.'));
        }

        if ($args["label"] == "") {
            $e->add(t('You must specify a text for the label.'));
        }

        if (intval($args["value"]) < 0 || intval($args["value"]) > 100) {
            $e->add(t('You must specify a valid value between 0 and 100.'));
        }

        if ($this->isValidColor($args["barColor"]) === false) {
            $e->add(t('You must specify a valid bar color.'));
        }

        if ($this->isValidColor($args["labelColor"]) === false) {
            $e->add(t('You must specify a valid label color.'));
        }

        if ($this->isValidColor($args["backgroundColor"]) === false) {
            $e->add(t('You must specify a valid background color.'));
        }

        return $e;
    }

}
