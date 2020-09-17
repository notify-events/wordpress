<?php

/**
 * @var View $this
 * @var Channel $channel
 */

use notify_events\models\Channel;
use notify_events\models\View;

?>

<form method="post">
    <table class="form-table">
        <tr class="wpne-form-group <?= $channel->has_error('title') ? 'wpne-has-error' : '' ?>">
            <th scope="row"><?= __('Title', WPNE) ?></th>
            <td>
                <input type="text" name="form[title]" value="<?= esc_html($channel->title) ?>" maxlength="200" class="regular-text">
                <?php if ($channel->has_error('title')) { ?>
                    <div class="wpne-error"><?= esc_html($channel->get_error('title')) ?></div>
                <?php } ?>
            </td>
        </tr>
        <tr class="wpne-form-group <?= $channel->has_error('token') ? 'wpne-has-error' : '' ?>">
            <th scope="row"><?= __('Token', WPNE) ?></th>
            <td>
                <input type="text" name="form[token]" value="<?= esc_html($channel->token) ?>" maxlength="32" class="regular-text">
                <?php if ($channel->has_error('token')) { ?>
                    <div class="wpne-error"><?= esc_html($channel->get_error('token')) ?></div>
                <?php } ?>
                <p class="description">
                    <?= __('You can acquire a token by adding Wordpress source in your channel on <a href="https://notify.events/en"">Notify.Events</a> website.', WPNE) ?>
                </p>
            </td>
        </tr>
    </table>

    <p class="submit">
        <input type="submit" class="button-primary" value="<?= __('Save Changes', WPNE) ?>">
    </p>
</form>
