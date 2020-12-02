<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<tr class="wpne-form-group <?= $event->has_error('title') ? 'wpne-has-error' : '' ?>">
    <th scope="row"><?= __('Title', WPNE) ?></th>
    <td>
        <input type="text" name="form[title]" value="<?= esc_attr($event->title) ?>" class="large-text">
        <?php if ($event->has_error('title')) { ?>
            <div class="wpne-error"><?= esc_html($event->get_error('title')) ?></div>
        <?php } ?>
    </td>
</tr>
