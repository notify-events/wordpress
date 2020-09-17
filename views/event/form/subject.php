<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<tr class="wpne-form-group <?= $event->has_error('subject') ? 'wpne-has-error' : '' ?>">
    <th scope="row"><?= __('Subject', WPNE) ?></th>
    <td>
        <?= $this->render('_tags', [
            'for'   => 'wpne_subject',
            'event' => $event,
        ]) ?>

        <input type="text" name="form[subject]" id="wpne_subject" class="large-text" maxlength="255" value="<?= esc_html($event->subject) ?>">
        <?php if ($event->has_error('subject')) { ?>
            <div class="wpne-error"><?= esc_html($event->get_error('subject')) ?></div>
        <?php } ?>
    </td>
</tr>
