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
use Concrete\Core\Cache\Level\ExpensiveCache;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\File\Image\BasicThumbnailer;
use Concrete\Core\File\Set\Set;
use Concrete\Core\File\Set\SetList;
use Concrete\Core\File\Type\Type as FileType;
use Concrete\Core\File\FileList;

class Controller extends BlockController
{
    protected $btTable = 'btMasonryGrid';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockOutputLifetime = 300;

    public function getBlockTypeDescription(): string
    {
        return t("Easily display your file sets from the file manager in a grid!");
    }

    public function getBlockTypeName(): string
    {
        return t("Masonry Grid");
    }

    public function add()
    {
        $this->set("fileSets", $this->getAllAvailableFileSets());
        $this->set("selectedFileSets", array_keys($this->getSelectedFileSets()));
    }


    public function edit()
    {
        $this->set("fileSets", $this->getAllAvailableFileSets());
        $this->set("selectedFileSets", array_keys($this->getSelectedFileSets()));
    }

    public function view()
    {
        $this->set("fileSets", $this->getSelectedFileSets());
        $this->set("images", $this->getImages());
    }

    public function getSearchableContent(): string
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
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $db->executeQuery("DELETE FROM btMasonryGridFileSets WHERE bID = ?", [$this->bID]);

        parent::delete();
    }

    public function save($args)
    {
        parent::save($args);

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $db->executeQuery("DELETE FROM btMasonryGridFileSets WHERE bID = ?", array($this->bID));

        if (is_array($this->request->request->get("fileSets"))) {
            foreach ($this->request->request->get("fileSets") as $fileSetId) {
                /** @noinspection PhpUnhandledExceptionInspection */
                /** @noinspection SqlDialectInspection */
                /** @noinspection SqlNoDataSourceInspection */
                $db->executeQuery(
                    "INSERT INTO btMasonryGridFileSets (bID, fileSetId) VALUES (?, ?)",

                    [
                        $this->bID,
                        (int)$fileSetId
                    ]
                );
            }
        }

        /** @var $cache ExpensiveCache */
        $cache = $this->app->make(ExpensiveCache::class);
        $cacheItem = $cache->getItem('bitter_theme.masonry_grid.images_' . $this->getBlockIdentifier());
        $cacheItem->clear();
    }

    private function getBlockIdentifier(): string
    {
        /** @noinspection PhpDeprecationInspection */
        return $this->getBlockObject()->getProxyBlock()
            ? $this->getBlockObject()->getProxyBlock()->getInstance()->getIdentifier()
            : $this->getIdentifier();
    }

    public function duplicate($newBID)
    {
        parent::duplicate($newBID);

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        foreach ($this->getSelectedFileSets() as $fileSetId => $fileSetName) {
            /** @noinspection PhpUnhandledExceptionInspection */
            /** @noinspection SqlDialectInspection */
            /** @noinspection SqlNoDataSourceInspection */
            $db->executeQuery(
                "INSERT INTO btMasonryGridFileSets (bID, fileSetId) VALUES (?, ?)",

                [
                    $newBID,
                    (int)$fileSetId
                ]
            );
        }
    }

    /**
     * @return array
     */
    private function getSelectedFileSets(): array
    {
        $fileSets = [];

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $rows = $db->fetchAll(
            "SELECT fileSetId FROM btMasonryGridFileSets WHERE bID = ?",
            [
                $this->bID
            ]
        );

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $fileSetId = (int)$row["fileSetId"];
                $fileSets[$fileSetId] = $this->getFileSetName($fileSetId);
            }
        }

        return $fileSets;
    }

    private function getFileSetName($fileSetId): string
    {
        $fileSet = Set::getByID($fileSetId);

        if ($fileSet instanceof Set) {
            return $fileSet->getFileSetName();
        } else {
            return "";
        }
    }

    private function getAllImagesInFileSet($fileSetId): array
    {
        $files = [];

        $fileSet = Set::getByID($fileSetId);

        if ($fileSet instanceof Set) {
            $fileList = new FileList();
            $fileList->ignorePermissions();
            $fileList->filterBySet($fileSet);
            $fileList->filterByType(FileType::T_IMAGE);
            $fileList->sortByFileSetDisplayOrder();
            $fileList->setItemsPerPage(10000);
            $files = $fileList->getResults();
        }

        return $files;
    }

    /**
     * @return array
     */
    private function getImages(): array
    {
        $images = [];

        $ttl = 24 * 60 * 60 * 30;

        /** @var $cache ExpensiveCache */
        $cache = $this->app->make('cache/expensive');

        $cacheItem = $cache->getItem('bitter_theme.masonry_grid.images_' . $this->getBlockIdentifier());

        if ($cacheItem->isMiss()) {
            /** @var BasicThumbnailer $imageHelper */
            $imageHelper = $this->app->make(BasicThumbnailer::class);

            foreach (array_keys($this->getSelectedFileSets()) as $fileSetId => $fileSetName) {
                foreach ($this->getAllImagesInFileSet($fileSetName) as $fileObject) {
                    $fileId = $fileObject->getFileID();

                    if (isset($images[$fileId]) === false) {
                        $images[$fileId] = [
                            "fileId" => $fileId,
                            "fileObject" => $fileObject,
                            "title" => $fileObject->getTitle(),
                            "description" => strlen($fileObject->getDescription()) > 0 ? $fileObject->getDescription() : t("No description available."),
                            "url" => $fileObject->getURL(),
                            "width" => (int)$fileObject->getAttribute('width'),
                            "height" => (int)$fileObject->getAttribute('height'),
                            "ratio" => (int)$fileObject->getAttribute('width') / (int)$fileObject->getAttribute('height'),
                            "fileSets" => array($fileSetName),
                            "thumbnail" => $imageHelper->getThumbnail($fileObject, 600, 600 * (int)$fileObject->getAttribute('width') / (int)$fileObject->getAttribute('height'), false)->src
                        ];
                    } else {
                        array_push($images[$fileId]["fileSets"], $fileSetId);
                    }
                }
            }

            $cacheItem->lock();
            $cache->save($cacheItem->set($images)->expiresAfter($ttl));
        } else {
            $images = $cacheItem->get();
        }

        return $images;
    }

    private function getAllAvailableFileSets(): array
    {
        $fileSets = [];

        $fileSetList = new SetList();

        foreach ($fileSetList->get(1000) as $fileSet) {
            $fileSets[$fileSet->getFileSetID()] = $fileSet->getFileSetName();
        }

        return $fileSets;
    }
}
