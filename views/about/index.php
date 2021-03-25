<?php

/**
 * @var View $this
 */

use notify_events\helpers\Url;
use notify_events\models\View;

?>

<div id="notify-events-about">

    <h2>
        <?= __('Start getting WordPress notifications in a convenient way with <a href="https://notify.events/" target="_blank">Notify.Events</a>', WPNE) ?>
    </h2>

    <p>
        <?= __('Notify.Events plugin is the ultimate tool for receiving notifications from your WordPress website to 30+ messenger apps and platforms: SMS, VoiceCall, Facebook Messenger, Viber, Telegram, and many more.', WPNE) ?>
    </p>

    <h3>
        <?= __('Channels', WPNE) ?>
    </h3>

    <p>
        <?= __('Add the channel that you want to be notified to.', WPNE) ?><br>
        <i>
            <?= __('Requires preliminary configuration in the Notify.Events account.', WPNE) ?>
        </i>
    </p>

    <h3>
        <?= __('Events', WPNE) ?>
    </h3>

    <p>
        <?= __('Add the events that you want to be notified about: new comment in the blog, new order, submitted contact form, and more', WPNE) ?>
    </p>

    <h3>
        <?= __('Configuration', WPNE) ?>
    </h3>

    <p>
        <?= __('Step 1. Notify.Events service configuration:', WPNE) ?>
    </p>
    <ol>
        <li><?= __('Sign up for Notify.Events on the <a href="https://notify.events/" target="_blank">official website</a>.', WPNE) ?></li>
        <li><?= __('Create a channel.', WPNE) ?></li>
        <li><?= __('Subscribe to the channel with your favorite instant messenger or choose another way to receive notifications.', WPNE) ?></li>
        <li><?= __('Add the "<a href="https://notify.events/source/wordpress" target="_blank">WordPress</a>" source to your channel and copy the generated token.', WPNE) ?></li>
    </ol>

    <p>
        <?= __('Step 2. WordPress configuration:', WPNE) ?>
    </p>
    <ol>
        <li><?= sprintf(__('Go to the "<a href="%s">Channels</a>" tab, create a new channel, and paste the copied token.', WPNE), Url::to(['controller' => 'channel'])) ?></li>
        <li><?= sprintf(__('Go to the "<a href="%s">Events</a>" tab and add the event you need.', WPNE), Url::to(['controller' => 'event'])) ?></li>
        <li><?= __('Define the event properties, customize the message text (if necessary), and save the changes. Make sure to select the desired channel and check the box next to ‘Enabled”. That’s it!', WPNE) ?></li>
    </ol>

</div>
