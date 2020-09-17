<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<tr class="wpne-form-group <?= $event->has_error('priority') ? 'wpne-has-error' : '' ?>">
    <th scope="row"><?= __('Message priority', WPNE) ?></th>
    <td>
        <select name="form[priority]">
            <?php foreach ($event->priority_labels() as $priority => $title) { ?>
                <option value="<?= esc_attr($priority) ?>" <?= ($event->priority == $priority) ? 'selected' : '' ?>><?= esc_html($title) ?></option>
            <?php } ?>
        </select>
        <?php if ($event->has_error('priority')) { ?>
            <div class="wpne-error"><?= esc_html($event->get_error('priority')) ?></div>
        <?php } ?>
    </td>
</tr>
