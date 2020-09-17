<?php

/**
 * @var View   $this
 * @var Event  $event
 * @var string $preview_subject
 * @var string $preview_message
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<div id="notify-events-event-form">

    <h2>
        <?= esc_html($event->title) ?>
    </h2>

    <form method="post">
        <?= $this->render('_header', [
            'event'           => $event,
            'preview_subject' => $preview_subject,
            'preview_message' => $preview_message,
        ]) ?>

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
            <input type="submit" class="button button-primary" value="<?= __('Save Changes', WPNE) ?>">
            <input type="submit" class="button" name="preview" value="<?= __('Preview', WPNE) ?>">
        </p>
    </form>

</div>