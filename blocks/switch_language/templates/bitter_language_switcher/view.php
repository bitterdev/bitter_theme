<?php

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Url;

/* @var array $languages */
/* @var int|null $activeLanguage */
/* @var int $cID */

?>

<?php if ($c instanceof Page && $c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Empty Auto-Nav Block.') ?>
    </div>
<?php } elseif (count($languages) > 1) { ?>
    <nav class="language-switcher" data-label="<?php echo h(t("Switch Langauge")); ?>">
        <ul>
            <?php foreach ($languages as $languageId => $languageName) { ?>
                <li>
                    <a href="<?php echo Url::to(Page::getCurrentPage(), 'switch_language', $cID, $languageId); ?>" <?php echo $languageId === $activeLanguage ? " class=\"active\"" : "" ?>>
                        <?php echo $languageName; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
<?php } ?>