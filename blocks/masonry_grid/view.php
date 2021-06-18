<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');


?>

<?php if (is_object(Page::getCurrentPage()) && Page::getCurrentPage()->isEditMode()): ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Block is disabled in edit mode.') ?>
    </div>
<?php else: ?>
    <?php $uid = uniqid(); ?>

    <div id="masonry-grid-<?php echo $uid; ?>" class="masonry-grid">
        <?php if (count($fileSets) > 1): ?>
            <ul class="filter">
                <?php if (!$disableViewAll): ?>
                    <li class="active" data-file-set-id="">
                        <?php echo t("View All"); ?>
                    </li>
                <?php endif; ?>

                <?php $firstIteration = true; ?>
                <?php foreach($fileSets as $fileSetId => $fileSetName): ?>
                    <?php
                        $className = "";

                        if ($disableViewAll && $firstIteration) {
                            $className = "active";
                            $firstIteration = false;
                        }
                    ?>

                    <li data-file-set-id="<?php echo $fileSetId; ?>" class="<?php echo $className; ?>">
                        <?php echo $fileSetName; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="images" itemscope itemtype="http://schema.org/ImageGallery">
            <?php foreach($images as $image): ?>
                <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" class="image" data-file-set-ids="<?php echo implode(", ", $image["fileSets"]); ?>">
                    <a href="<?php echo $image["url"]; ?>" itemprop="contentUrl" data-size="<?php echo sprintf("%sx%s", $image["width"], $image["height"]); ?>">
                        <img src="<?php echo $image["thumbnail"]; ?>" itemprop="thumbnail" alt="<?php echo addslashes($image["description"]); ?>" />
                    </a>

                    <figcaption itemprop="caption description">
                        <strong>
                            <?php echo $image["title"]; ?>
                        </strong>

                        <br>

                        <?php echo $image["description"]; ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>

    <style type="text/css">
        #masonry-grid-<?php echo $uid; ?> .filter li {
            background-color: <?php echo $backgroundColorNormal; ?>;
            color: <?php echo $textColorNormal; ?>;
        }

        #masonry-grid-<?php echo $uid; ?> .filter li:hover,
        #masonry-grid-<?php echo $uid; ?> .filter li.active {
            background-color: <?php echo $backgroundColorActive; ?>;
            color: <?php echo $textColorActive; ?>;
        }
    </style>

    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                masonryGrid({
                    bID: '<?php echo $uid; ?>',
                    i18n: {
                        close: "<?php echo t("Close (Esc)"); ?>",
                        share: "<?php echo t("Share"); ?>",
                        fullscreen: "<?php echo t("Toggle fullscreen"); ?>",
                        zoom: "<?php echo t("Zoom in/out"); ?>",
                        prev: "<?php echo t("Previous (arrow left)"); ?>",
                        next: "<?php echo t("Next (arrow right)"); ?>"
                    }
                });
            });
        })(jQuery);
    </script>
<?php endif; ?>