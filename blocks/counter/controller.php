<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme\Block\Counter;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Concrete\Package\Counter\Src\CounterItems;
use Core;

class Controller extends BlockController {

    public $helpers = array(
        'form',
    );

    public $btFieldsRequired = array(
        'backgroundColor',
        'textColor',
        'time'
    );

    protected $btExportFileColumns = array();
    protected $btTable = 'btCounter';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputLifetime = 300;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;

    public function getBlockTypeDescription() {
        return t("Block element for animating numbers on your website.");
    }

    public function getBlockTypeName() {
        return t("Counter Up");
    }

    public function view() {
        $this->requireAsset("javascript", "jquery");

        $items = CounterItems::getInstance()->getItems($this->bID);

        $this->set("items", $items);
    }

    public function add() {
        $this->requireAsset("javascript", "mustache.js");

        $this->set("items", array());
    }

    public function getSearchableContent() {
        $content = "";

        $items = CounterItems::getInstance()->getItems($this->bID);

        if (is_array($items)) {
            foreach($items as $item) {
                $content .= ($content != "" ? " " : "") . $item->getCounterDescription();
            }
        }

        return $content;
    }

    public function edit() {
        $this->requireAsset("javascript", "mustache.js");

        $items = CounterItems::getInstance()->getItemsAsArray($this->bID);

        $this->set("items", $items);
    }

    public function delete() {
        CounterItems::getInstance()->removeItems($this->bID);
    }

    public function save($args) {
        parent::save($args);

        CounterItems::getInstance()->setItems($this->bID, $args["items"]);
    }

    /**
     * @param string $color
     *
     * @return boolean
     */
    private function isValidColor($color) {
        $allColors = array('transparent', 'aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');

        if (in_array(strtolower($color), $allColors)) {
            return true;
        } else if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return true;
        } else if (preg_match('/^[a-f0-9]{6}$/i', $color)) {
            return true;
        }

        return false;
    }

    public function duplicate($newBID) {
        parent::duplicate($newBID);

        CounterItems::getInstance()->duplicateItems($this->bID, $newBID);
    }

    public function validate($args) {
        $e = Core::make('helper/validation/error');

        if (!$args['backgroundColor'] || !$this->isValidColor($args['backgroundColor'])) {
            $e->add(t('You must specify a valid background color.'));
        }

        if (!$args['textColor'] || !$this->isValidColor($args['textColor'])) {
            $e->add(t('You must specify a valid text color.'));
        }

        if (!$args['time'] || !is_numeric($args['time']) || intval($args['time']) < 0) {
            $e->add(t('You must specify a valid time.'));
        }

        if (isset($args["items"]) && is_array($args["items"])) {
            $missingCounterValue = false;

            foreach($args["items"] as $item) {
                if (intval($item["counterValue"]) === 0) {
                    $missingCounterValue = true;

                    break;
                }
            }

            if ($missingCounterValue) {
                $e->add(t('You must specify a valid counter value.'));
            }
        }

        return $e;
    }

}
