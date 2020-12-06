<?php

/**
 * @var View $this
 */

use notify_events\helpers\Url;
use notify_events\models\View;

?>

<div id="notify-events-about">

    <h2>
        <?= __('Start getting notification experience for WordPress with <a href="https://notify.events/" target="_blank">Notify.Events</a>', WPNE) ?>
    </h2>

    <p>
        <?= __('Notify.Events plugin is ultimate tool for any kind of notifications from your WordPress website to more than 20 messengers and platfroms such as SMS, VoiceCall, Facebook Messenger, Viber, Telegram and many more.', WPNE) ?>
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
        <?= __('Add event which you want to be notified, new comment in blog, new order or submitted contact form, for example.', WPNE) ?>
    </p>

    <h3>
        <?= __('Configuration', WPNE) ?>
    </h3>

    <p>
        <?= __('Notify.Events service configuration:', WPNE) ?>
    </p>
    <ol>
        <li><?= __('Sign up on a website <a href="https://notify.events/" target="_blank">Notify.Events</a>', WPNE) ?></li>
        <li><?= __('Create a channel', WPNE) ?></li>
        <li><?= __('Subscribe to the channel with your favorite messenger', WPNE) ?></li>
        <li><?= __('Add "<a href="https://notify.events/source/wordpress" target="_blank">WordPress</a>" source to your channel and copy token', WPNE) ?></li>
    </ol>

    <p>
        <?= __('WordPress configuration:', WPNE) ?>
    </p>
    <ol>
        <li><?= sprintf(__('Go to the "<a href="%s">Channels</a>" tab, create new channel and put a token', WPNE), Url::to(['controller' => 'channel'])) ?></li>
        <li><?= sprintf(__('Go to the "<a href="%s">Events</a>" tab and add an event', WPNE), Url::to(['controller' => 'event'])) ?></li>
        <li><?= __('Define event properties and submit form. Done!', WPNE) ?></li>
    </ol>

</div>
