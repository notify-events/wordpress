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
    <th scope="row"><?= __('Channel', WPNE) ?></th>
    <td>
        <select name="form[channel_id]">
            <option value="" disabled <?= empty($event->channel_id) ? 'selected' : '' ?>><?= __('- Select channel -', WPNE) ?></option>
            <?php foreach (Core::channel_list() as $id => $title) { ?>
                <option value="<?= esc_attr($id) ?>" <?= ($event->channel_id == $id) ? 'selected' : '' ?>><?= esc_html($title) ?></option>
            <?php } ?>
        </select>
        <?php if ($event->has_error('channel_id')) { ?>
            <div class="wpne-error"><?= esc_html($event->get_error('channel_id')) ?></div>
        <?php } ?>
    </td>
</tr>
