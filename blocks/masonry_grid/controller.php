<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme\Block\MasonryGrid;

use Concrete\Core\Block\BlockController;
use Concrete\Core\File\Set\SetList as FileSetList;
use Concrete\Core\File\Type\Type as FileType;
use Concrete\Core\File\FileList;
use FileSet;
use Database;
use Core;
use Config;

class Controller extends BlockController
{

    public $helpers = array(
        'form',
    );

    protected $btExportFileColumns = array();
    protected $btTable = 'btMasonryGrid';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputLifetime = 300;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;

    private $db;
    private $securityHelper;

    /**
     * @param BlockType $obj |Block $obj
     */
    public function __construct($obj = null)
    {
        parent::__construct($obj);

        $this->db = Database::connection();

        $this->securityHelper = Core::make('helper/security');
    }

    public function getBlockTypeDescription()
    {
        return t("Easily display your file sets from the file manager in a grid!");
    }

    public function getBlockTypeName()
    {
        return t("Masonry Grid");
    }

    public function add()
    {
        $this->set("backgroundColorNormal", "transparent");
        $this->set("textColorNormal", "#75ca2a");
        $this->set("backgroundColorActive", "#75ca2a");
        $this->set("textColorActive", "#ffffff");

        $this->addOrEdit();
    }


    public function edit()
    {
        $this->addOrEdit();
    }

    public function registerViewAssets($outputContent = '')
    {
        parent::registerViewAssets($outputContent);

        $this->requireAsset('javascript', 'jquery');
        $this->requireAsset('javascript', 'macy');
        $this->requireAsset('photoswipe');
        $this->requireAsset('photoswipe/default-skin');
    }

    public function view()
    {

        $this->set("fileSets", $this->getSelectedFileSets());
        $this->set("images", $this->getImages());
    }

    public function getSearchableContent()
    {
        $content = "";

        foreach ($this->getSelectedFileSets() as $fileSetName) {
            $content .= sprintf("%s ", $fileSetName);
        }

        foreach ($this->getImages() as $image) {
            $content .= sprintf("%s ", $image["title"]);
        }

        return $content;
    }

    public function delete()
    {
        parent::delete();

        $this->db->executeQuery("DELETE FROM btMasonryGridFileSets WHERE bID = ?", array($this->bID));
    }

    public function save($args)
    {
        if (!isset($args["disableNoDescription"])) {
            $args["disableNoDescription"] = 0;
        }

        if (!isset($args["disableViewAll"])) {
            $args["disableViewAll"] = 0;
        }

        parent::save($args);

        $this->db->executeQuery("DELETE FROM btMasonryGridFileSets WHERE bID = ?", array($this->bID));

        if (is_array($this->post("fileSets"))) {
            foreach ($this->post("fileSets") as $index => $fileSetId) {
                $this->db->executeQuery(
                    "INSERT INTO btMasonryGridFileSets (bID, fileSetId) VALUES (?, ?)",

                    array(
                        $this->bID,
                        $this->securityHelper->sanitizeInt($fileSetId)
                    )
                );
            }
        }

        // Clear Cache

        if (version_compare(APP_VERSION, '8.0', '>=')) {
            /** @var $cache \Concrete\Core\Cache\Level\ExpensiveCache */
            $cache = $this->app->make('cache/expensive');
            $cacheItem = $cache->getItem('bitter.masonry_grid.images_' . $this->getBlockIdentifier());
            $cacheItem->clear();
        } else {
            /** @var $cache \Concrete\Core\Cache\Level\ExpensiveCache */
            $cache = \Core::make('cache/expensive');
            $cacheItem = $cache->getItem('bitter.masonry_grid.images_' . $this->getBlockIdentifier());
            $cacheItem->clear();
        }
    }

    private function getBlockIdentifier()
    {
        return $this->getBlockObject()->getProxyBlock()
            ? $this->getBlockObject()->getProxyBlock()->getInstance()->getIdentifier()
            : $this->getIdentifier();
    }

    public function duplicate($newBID)
    {
        parent::duplicate($newBID);

        foreach ($this->getSelectedFileSets() as $fileSetId => $fileSetName) {
            $this->db->executeQuery(
                "INSERT INTO btMasonryGridFileSets (bID, fileSetId) VALUES (?, ?)",

                array(
                    $newBID,
                    $this->securityHelper->sanitizeInt($fileSetId)
                )
            );
        }
    }

    private function addOrEdit()
    {
        $this->set("fileSets", $this->getAllAvailableFileSets());
        $this->set("selectedFileSets", array_keys($this->getSelectedFileSets()));
    }

    /**
     * @return array
     */
    private function getSelectedFileSets()
    {
        $fileSets = array();

        $rows = $this->db->fetchAll(
            "SELECT fileSetId FROM btMasonryGridFileSets WHERE bID = ?",

            array(
                $this->bID
            )
        );

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $fileSetId = $this->securityHelper->sanitizeInt($row["fileSetId"]);

                $fileSets[$fileSetId] = $this->getFileSetName($fileSetId);
            }
        }

        return $fileSets;
    }

    /**
     * @param integer $fileSetId
     *
     * @return string
     */
    private function getFileSetName($fileSetId)
    {
        $fileSet = FileSet::getByID($fileSetId);

        if (is_object($fileSet)) {
            return $fileSet->getFileSetName();
        } else {
            return "";
        }
    }

    /**
     * @param integer $fileSetId
     *
     * @return string
     */
    private function getAllImagesInFileSet($fileSetId)
    {
        $files = array();

        $fileSet = FileSet::getByID($fileSetId);

        if (is_object($fileSet)) {
            $fileList = new FileList();

            if (version_compare(Config::get('concrete.version'), '8.2', '>=')) {
                $fileList->ignorePermissions();
            }

            $fileList->filterBySet($fileSet);
            $fileList->filterByType(FileType::T_IMAGE);
            $fileList->sortByFileSetDisplayOrder();

            if ($this->numberFiles > 0) {
                $fileList->setItemsPerPage($this->numberFiles);
            } else {
                $fileList->setItemsPerPage(10000);
            }

            $files = $fileList->getResults();
        }

        return $files;
    }


    /**
     * @return array
     */
    private function getLiveImages()
    {
        $images = array();

        $imageHelper = Core::make('helper/image');

        foreach (array_keys($this->getSelectedFileSets()) as $fileSetId => $fileSetName) {
            foreach ($this->getAllImagesInFileSet($fileSetName) as $fileObject) {
                $fileId = $fileObject->getFileID();

                if (isset($images[$fileId]) === false) {
                    $images[$fileId] = array(
                        "fileId" => $fileId,
                        "fileObject" => $fileObject,
                        "title" => $fileObject->getTitle(),
                        "description" => strlen($fileObject->getDescription()) > 0 ? $fileObject->getDescription() : ($this->disableNoDescription ? "" : t("No description available.")),
                        "url" => $fileObject->getURL(),
                        "width" => intval($fileObject->getAttribute('width')),
                        "height" => intval($fileObject->getAttribute('height')),
                        "ratio" => intval($fileObject->getAttribute('width')) / intval($fileObject->getAttribute('height')),
                        "fileSets" => array($fileSetName),
                        "thumbnail" => $imageHelper->getThumbnail($fileObject, 600, 600 * intval($fileObject->getAttribute('width')) / intval($fileObject->getAttribute('height')), false)->src
                    );
                } else {
                    array_push($images[$fileId]["fileSets"], $fileSetId);
                }
            }
        }

        return $images;
    }

    /**
     * @return array
     */
    private function getImages()
    {
        $ttl = 24 * 60 * 60 * 30; // 1 month

        if (version_compare(APP_VERSION, '8.0', '>=')) {

            /** @var $cache \Concrete\Core\Cache\Level\ExpensiveCache */
            $cache = $this->app->make('cache/expensive');

            $cacheItem = $cache->getItem('bitter.masonry_grid.images_' . $this->getBlockIdentifier());

            if ($cacheItem->isMiss()) {
                $cacheItem->lock();
                $images = $this->getLiveImages();
                $cache->save($cacheItem->set($images)->expiresAfter($ttl));
            } else {
                $images = $cacheItem->get();
            }
        } else {

            /** @var $cache \Concrete\Core\Cache\Level\ExpensiveCache */
            $cache = \Core::make('cache/expensive');

            $cacheItem = $cache->getItem('bitter.masonry_grid.images_' . $this->getBlockIdentifier());

            if ($cacheItem->isMiss()) {
                $cacheItem->lock();
                $images = $this->getLiveImages();
                $cacheItem->set($images, $ttl); // expire after 300 seconds
            } else {
                $images = $cacheItem->get();
            }
        }

        return $images;
    }

    /**
     * @return array
     */
    private function getAllAvailableFileSets()
    {
        $fileSets = array();

        $fileSetList = new FileSetList();

        foreach ($fileSetList->get(1000, 0) as $fileSet) {
            $fileSets[$fileSet->getFileSetID()] = $fileSet->getFileSetName();
        }

        return $fileSets;
    }

}
