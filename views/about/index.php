<?php

/**
 * @var View   $this
 * @var string $plugin_woo
 * @var string $plugin_cf7
 * @var string $plugin_wpf
 * @var string $plugin_njf
 */

use notify_events\models\View;

?>

<div id="notify-events-about">

    <h2>
        <?= __('Start getting notification experience for WordPress with <a href="https://notify.events/" target="_blank">Notify.Events</a>', WPNE) ?>
    </h2>

    <p>
        <?= __('Notify.Events plugin is ultimate tool for any kind of notifications from your WordPress website to more than 20 messengers and platfroms such as SMS, VoiceCall, Facebook Messenger, Viber, Telegram and many more', WPNE) ?>
    </p>

    <h3>
        <?= __('Channels', WPNE) ?>
    </h3>

    <p>
        <?= __('Add channel where you would like to receive your notification.', WPNE) ?>
    </p>

    <h3>
        <?= __('Events', WPNE) ?>
    </h3>

    <p>
        <?= __('Add event which you want to be notified, new comment in blog, new order or submitted contact form, for example', WPNE) ?>
    </p>

    <h3>
        <?= __('Installation', WPNE) ?>
    </h3>

    <ol>
        <li><?= __('Sign up on a website <a href="https://notify.events/" target="_blank">Notify.Events</a>', WPNE) ?></li>
        <li><?= __('Create and name a channel', WPNE) ?></li>
        <li><?= __('Choose a messenger in your channel and subscribe', WPNE) ?></li>
        <li><?= __('Go back to Wordpress plugin', WPNE) ?></li>
        <li><?= __('Install additional plugins if necessary from links below', WPNE) ?></li>
        <li><?= __('Go to the Channels tab and paste a token', WPNE) ?></li>
        <li><?= __('Go to the Events tab and add an event', WPNE) ?></li>
        <li><?= __('Fill the inputs and save settings. Done!', WPNE) ?></li>
    </ol>

</div>
