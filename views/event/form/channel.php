<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Core;
use notify_events\models\Event;
use notify_events\models\View;

?>

<tr class="wpne-form-group <?= $event->has_error('channel_id') ? 'wpne-has-error' : '' ?>">
    <th scope="row"><?= __('Channels', WPNE) ?></th>
    <td>
        <?php foreach (Core::channel_list() as $id => $title) { ?>
            <label>
                <input type="checkbox" name="form[channel_id][]" value="<?= $id ?>" <?= in_array($id, (array)$event->channel_id) ? 'checked' : '' ?>>
                <?= esc_html($title) ?>
            </label><br>
        <?php } ?>
        <?php if ($event->has_error('channel_id')) { ?>
            <div class="wpne-error"><?= esc_html($event->get_error('channel_id')) ?></div>
        <?php } ?>
    </td>
</tr>
