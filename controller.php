<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\BitterTheme;

use Bitter\BitterTheme\Provider\ServiceProvider;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\Importer;
use Concrete\Core\File\Set\Set;
use Concrete\Core\Package\Package;
use Concrete\Theme\Concrete\PageTheme;
use Exception;

class Controller extends Package
{
    protected $pkgHandle = 'bitter_theme';
    protected $pkgVersion = '2.0.7';
    protected $appVersionRequired = '8.5.4';
    protected $pkgAllowsFullContentSwap = true;
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/BitterTheme' => 'Bitter\BitterTheme',
    ];

    public function getPackageDescription()
    {
        return t('Powerful Theme for ConcreteCMS.');
    }

    public function getPackageName()
    {
        return t('Bitter Theme');
    }

    public function on_start()
    {
        if (file_exists($this->getPackagePath() . '/vendor/autoload.php')) {
            require_once($this->getPackagePath() . '/vendor/autoload.php');
        }

        /** @var ServiceProvider $serviceProvider */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function testForUninstall()
    {
        $pageTheme = PageTheme::getByHandle('elemental');
        if (is_object($pageTheme)) {
            $pageTheme->applyToSite();
        }
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
    }

    /*
     * This is the post install routine that is required to install content
     * that is not able to install by CIF file format.
     */
    public function on_after_swap_content()
    {
        /** @var Importer $importer */
        $importer = $this->app->make(Importer::class);
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        // import files and create file sets
        $fileSets = [
            "Best References" => "placeholder_square_white.jpg",
            "Other Projects" => "placeholder_square.jpg",
            "concrete5 Projects"=> "placeholder_square.jpg"
        ];

        foreach ($fileSets as $fileSetName => $sampleImageName) {
            $fileSet = Set::getByName($fileSetName);

            if (!$fileSet instanceof Set) {
                $fileSet = Set::createAndGetSet($fileSetName, Set::TYPE_PUBLIC, true);
            }

            for ($i = 1; $i <= 10; $i++) {
                try {
                    /** @noinspection PhpDeprecationInspection */
                    $fileVersion = $importer->import($this->getPackagePath() . "/content_files/" . $sampleImageName, sprintf("%s %s.jpg", $fileSetName, $i));

                    if ($fileVersion instanceof Version) {
                        $fileSet->addFileToSet($fileVersion);
                    }
                } catch (Exception $err) {
                    // No Nothing
                }
            }
        }

        // apply file sets for image carousel block types
        $imageCarousels = [
            [
                "id" => "home",
                "fileSetName" => "Best References"
            ]
        ];

        foreach ($imageCarousels as $imageCarousel) {
            /** @noinspection SqlDialectInspection */
            /** @noinspection SqlNoDataSourceInspection */
            $bID = $db->fetchColumn(
                "SELECT b.bID FROM Blocks AS b LEFT JOIN BlockTypes AS bt ON (b.btID = bt.btID) LEFT JOIN CollectionVersionBlockStyles AS cvbs ON (b.bID = cvbs.bID) LEFT JOIN StyleCustomizerInlineStyleSets AS sciss ON (cvbs.issID = sciss.issID) WHERE bt.btHandle = ? AND sciss.customID = ?",
                [
                    "image_carousel",
                    $imageCarousel["id"]
                ]
            );

            $fileSet = Set::getByName($imageCarousel["fileSetName"]);

            if ($fileSet instanceof Set) {
                $fileSetId = $fileSet->getFileSetID();

                /** @noinspection SqlDialectInspection */
                /** @noinspection SqlNoDataSourceInspection */
                /** @noinspection PhpUnhandledExceptionInspection */
                $db->executeQuery("UPDATE btImageCarousel SET fileSetId = ? WHERE bID = ?", [$fileSetId, $bID]);
            }
        }

        // apply file sets for masonry grid block types

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $rows = $db->fetchAll(
            "SELECT b.bID FROM Blocks AS b LEFT JOIN BlockTypes AS bt ON (b.btID = bt.btID) WHERE bt.btHandle = ? ",
            [
                "masonry_grid"
            ]
        );

        foreach ($rows as $row) {
            $fileSetNames = ["concrete5 Projects", "Other Projects"];

            foreach ($fileSetNames as $fileSetName) {
                $fileSet = Set::getByName($fileSetName);

                if ($fileSet instanceof Set) {
                    $fileSetId = $fileSet->getFileSetID();

                    /** @noinspection SqlDialectInspection */
                    /** @noinspection SqlNoDataSourceInspection */
                    /** @noinspection PhpUnhandledExceptionInspection */
                    $db->executeQuery("INSERT INTO btMasonryGridFileSets (bID, fileSetId) VALUES (?, ?)", [$row["bID"], $fileSetId]);
                }
            }
        }
    }
}
