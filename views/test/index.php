<?php

/**
 * @var View $this
 * @var Test $test
 */

use notify_events\models\Core;
use notify_events\models\Test;
use notify_events\models\View;

?>

<div id="notify-events-help">

    <h2><?= __('Test message sending', WPNE) ?></h2>

    <form method="post">
        <table class="form-table">
            <tr class="wpne-form-group <?= $test->has_error('channel_id') ? 'wpne-has-error' : '' ?>">
                <th scope="row"><?= __('Channel', WPNE) ?></th>
                <td>
                    <select name="form[channel_id]">
                        <option value="" disabled <?= empty($test->channel_id) ? 'selected' : '' ?>><?= __('- Select channel -', WPNE) ?></option>
                        <?php foreach (Core::channel_list() as $id => $title) { ?>
                            <option value="<?= esc_attr($id) ?>" <?= ($test->channel_id == $id) ? 'selected' : '' ?>><?= esc_html($title) ?></option>
                        <?php } ?>
                    </select>
                    <?php if ($test->has_error('channel_id')) { ?>
                        <div class="wpne-error"><?= esc_html($test->get_error('channel_id')) ?></div>
                    <?php } ?>
                </td>
            </tr>
            <tr class="wpne-form-group <?= $test->has_error('subject') ? 'wpne-has-error' : '' ?>">
                <th scope="row"><?= __('Subject', WPNE) ?></th>
                <td>
                    <input type="text" name="form[subject]" class="regular-text" maxlength="255" value="<?= esc_html($test->subject) ?>">
                    <?php if ($test->has_error('subject')) { ?>
                        <div class="wpne-error"><?= esc_html($test->get_error('subject')) ?></div>
                    <?php } ?>
                </td>
            </tr>
            <tr class="wpne-form-group <?= $test->has_error('message') ? 'wpne-has-error' : '' ?>">
                <th scope="row"><?= __('Message', WPNE) ?></th>
                <td>
                    <textarea name="form[message]" rows="4" class="regular-text" maxlength="4096"><?= esc_html($test->message) ?></textarea>
                    <?php if ($test->has_error('message')) { ?>
                        <div class="wpne-error"><?= esc_html($test->get_error('message')) ?></div>
                    <?php } ?>
                    <p class="description">
                        <?= __('You can use simple html tag: &lt;b&gt;, &lt;i&gt; and &lt;a&gt;', WPNE) ?>
                    </p>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?= __('Send Message', WPNE) ?>">
        </p>
    </form>

</div>
