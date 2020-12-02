<?php

/**
 * @var View              $this
 * @var OrderStatusChange $event
 * @var string            $preview_subject
 * @var string            $preview_message
 */

use notify_events\models\View;
use notify_events\modules\woocommerce\models\events\order\OrderStatusChange;

?>

<div class="wrap" id="notify-events-contact-form-7-event-form-submit">

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
            <tr class="wpne-form-group <?= $event->has_error('statuses') ? 'wpne-has-error' : '' ?>">
                <th scope="row"><?= __('Statuses', WPNE) ?></th>
                <td>
                    <?php foreach ($event->status_list() as $status => $title) { ?>
                        <label>
                            <input type="checkbox" name="form[statuses][]" value="<?= $status ?>" <?= in_array($status, (array)$event->statuses) ? 'checked' : '' ?>>
                            <?= esc_html($title) ?>
                        </label><br>
                    <?php } ?>
                    <?php if ($event->has_error('statuses')) { ?>
                        <div class="wpne-error"><?= esc_html($event->get_error('statuses')) ?></div>
                    <?php } ?>
                </td>
            </tr>
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