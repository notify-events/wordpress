<?php

/**
 * @var View   $view
 * @var string $for
 * @var Event  $event
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<div for="<?= esc_attr($for) ?>" class="wpne_form_tag_block">
    <div class="nav-tab-wrapper wpne-tabs" for="#<?= esc_attr($for) ?>-tabs">
        <?php foreach ($event->tag_labels() as $category => $tags) { ?>
            <a href="#" class="nav-tab"><?= esc_html($category) ?></a>
        <?php } ?>
    </div>

    <div id="<?= esc_attr($for) ?>-tabs" class="wpne-tabs-content">
        <?php foreach ($event->tag_labels() as $category => $tags) { ?>
            <div>
                <?php foreach ($tags as $tag => $label) { ?>
                    <button data-tag="<?= esc_html($tag) ?>" class="wpne_tag button">
                        <b><?= esc_html($tag) ?></b>
                        <?php if ($label !== false) { ?>
                            | <small><?= esc_html($label) ?></small>
                        <?php } ?>
                    </button>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
