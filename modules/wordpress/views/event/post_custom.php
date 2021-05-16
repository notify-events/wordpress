<?php

/**
 * @var View       $this
 * @var PostCustom $event
 * @var string     $preview_subject
 * @var string     $preview_message
 */

use notify_events\models\View;
use notify_events\modules\wordpress\models\events\post\PostCustom;

?>

<div class="wrap" id="notify-events-wordpress-event-post-custom">

    <h2>
        <?= esc_html($event->title) ?>
    </h2>

    <form method="post">
        <?= $this->render('_header', [
            'event'           => $event,
            'preview_subject' => $preview_subject,
            'preview_message' => $preview_message,
        ], false) ?>

        <table class="form-table">
            <?= $this->render('form/title', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/enabled', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/channel', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/_multi_select', [
                'model' => $event,
                'title' => __('Post Type', WPNE),
                'field' => 'post_type',
                'items' => $event::post_type_list(),
            ], false) ?>
            <?= $this->render('form/_multi_select', [
                'model' => $event,
                'title' => __('Post Status', WPNE),
                'field' => 'post_status',
                'items' => $event::post_status_list(),
            ], false) ?>
            <?= $this->render('form/subject', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/message', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/priority', [
                'event' => $event,
            ], false) ?>
        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?= __('Save Changes', WPNE) ?>">
            <input type="submit" class="button" name="preview" value="<?= __('Preview', WPNE) ?>">
        </p>
    </form>

</div>
