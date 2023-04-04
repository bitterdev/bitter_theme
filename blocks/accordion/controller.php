<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme\Block\Accordion;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Editor\CkeditorEditor;
use Concrete\Core\Error\ErrorList\ErrorList;

class Controller extends BlockController
{
    protected $btTable = 'btAccordion';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockOutputLifetime = 300;

    public function getBlockTypeDescription()
    {
        return t("Add accordion elements to your site.");
    }

    public function getBlockTypeName()
    {
        return t("Accordion");
    }

    public function view()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $this->set("items", $db->fetchAll("SELECT * FROM btAccordionItems WHERE bID = ?", [$this->bID]));
    }

    public function add()
    {
        $this->app->make(CkeditorEditor::class);
        $this->requireAsset('editor/ckeditor4');
        $this->set("items", []);
    }

    public function edit()
    {
        $this->app->make(CkeditorEditor::class);
        $this->requireAsset('editor/ckeditor4');
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $this->set("items", $db->fetchAll("SELECT * FROM btAccordionItems WHERE bID = ?", [$this->bID]));
    }

    public function getSearchableContent()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $items = $db->fetchAll("SELECT * FROM btAccordionItems WHERE bID = ?", [$this->bID]);

        $content = "";

        if (is_array($items)) {
            foreach ($items as $item) {
                $content .= sprintf(
                    "%s%s %s",
                    ($content != "" ? " " : ""),
                    $item["title"],
                    strip_tags($item["body"])
                );
            }
        }

        return $content;
    }

    public function delete()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection PhpUnhandledExceptionInspection */
        $db->executeQuery("DELETE FROM btAccordionItems WHERE bID = ?", [$this->bID]);

        parent::delete();
    }

    public function save($args)
    {
        parent::save($args);

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection PhpUnhandledExceptionInspection */
        $db->executeQuery("DELETE FROM btAccordionItems WHERE bID = ?", [$this->bID]);

        if (is_array($args["items"])) {
            foreach ($args["items"] as $item) {
                /** @noinspection SqlDialectInspection */
                /** @noinspection SqlNoDataSourceInspection */
                /** @noinspection PhpUnhandledExceptionInspection */
                $db->executeQuery("INSERT INTO btAccordionItems (bID, title, body) VALUES (?, ?, ?)", [
                    $this->bID,
                    $item["title"],
                    $item["body"]
                ]);
            }
        }
    }

    public function duplicate($newBID)
    {
        parent::duplicate($newBID);

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        $copyFields = 'title, body';
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection PhpUnhandledExceptionInspection */
        $db->executeUpdate("INSERT INTO btAccordionItems (bID, {$copyFields}) SELECT ?, {$copyFields} FROM btAccordionItems WHERE bID = ?", [
                $newBID,
                $this->bID
            ]
        );
    }

    public function validate($args)
    {
        $errorList = new ErrorList();

        if (isset($args["items"]) && is_array($args["items"])) {
            $missingTitle = false;

            foreach ($args["items"] as $item) {
                if (strlen($item["title"]) === 0) {
                    $missingTitle = true;

                    break;
                }
            }

            if ($missingTitle) {
                $errorList->add(t('You must specify a valid title for each item.'));
            }
        }

        return $errorList;
    }

}
