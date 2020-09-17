<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<tr class="wpne-form-group <?= $event->has_error('message') ? 'wpne-has-error' : '' ?>">
    <th scope="row"><?= __('Message', WPNE) ?></th>
    <td>
        <?= $this->render('_tags', [
            'for'   => 'wpne_message',
            'event' => $event,
        ]) ?>

        <textarea name="form[message]" id="wpne_message" rows="10" class="large-text" maxlength="4096"><?= esc_html($event->message) ?></textarea>
        <?php if ($event->has_error('message')) { ?>
            <div class="wpne-error"><?= esc_html($event->get_error('message')) ?></div>
        <?php } ?>
        <p class="description">
            <?= __('You can use simple html tag: &lt;b&gt;, &lt;i&gt; and &lt;a&gt;', WPNE) ?>
        </p>
    </td>
</tr>
