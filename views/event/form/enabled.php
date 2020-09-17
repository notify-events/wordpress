<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<tr class="wpne-form-group <?= $event->has_error('enabled') ? 'wpne-has-error' : '' ?>">
    <th scope="row"></th>
    <td>
        <label>
            <input type="checkbox" name="form[enabled]" <?= $event->enabled ? 'checked' : '' ?>>
            <?= __('Enabled', WPNE) ?>
        </label>
        <?php if ($event->has_error('enabled')) { ?>
            <div class="wpne-error"><?= esc_html($event->get_error('enabled')) ?></div>
        <?php } ?>
    </td>
</tr>
