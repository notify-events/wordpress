<?php

/**
 * @var View   $this
 * @var string $plugin_woo
 * @var string $plugin_cf7
 * @var string $plugin_wpf
 */

use notify_events\models\View;

?>

<div id="notify-events-about">

    <h2>
        <?= __('Start getting notification experience for WordPress with <a href="https://notify.events/" target="_blank">Notify.Events</a>', WPNE) ?>
    </h2>

    <p>
        <?= __('Notify.Events plugin is ultimate tool for any kind of notifications from your WordPress website to more than 20 messengers and platfroms such as SMS, voicecall, Facebook messenger, Viber, Telegram and many more', WPNE) ?>
    </p>

    <h3>
        <?= __('Channels', WPNE) ?>
    </h3>

    <p>
        <?= __('Add channel where you would like to receive your notification: SMS, Viber, VK, FB messenger and etc.', WPNE) ?>
    </p>

    <h3>
        <?= __('Events', WPNE) ?>
    </h3>

    <p>
        <?= __('Add event which you want to be notified, new comment in blog or submitted contact form, for example', WPNE) ?>

    </p>

    <h3>
        <?= __('Instruction', WPNE) ?>
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
<!--
    <h3>
        <?= __('Add-on', WPNE) ?>
    </h3>

    <p>
        <?= __('You can use additional plugin to expand your notification experience:', WPNE) ?>
    </p>

    <table class="widefat importers striped">
        <tr>
            <th>
                <strong><a href="https://wordpress.org/plugins/notify-events-woocommerce" target="_blank">WooCommerce</a></strong>
            </th>
            <td>
                <?= $plugin_woo ?>
            </td>
            <td>
                <?= __('Plugin for notification from your E-Shop on WooCommerce', WPNE) ?>
            </td>
        </tr>
        <tr>
            <th>
                <strong><a href="https://wordpress.org/plugins/notify-events-contact-form-7" target="_blank">Contact Form 7</a></strong>
            </th>
            <td>
                <?= $plugin_cf7 ?>
            </td>
            <td>
                <?= __('Plugin for notification from Contact Form 7', WPNE) ?>
            </td>
        </tr>
        <tr>
            <th>
                <strong><a href="https://wordpress.org/plugins/notify-events-wpforms" target="_blank">WPForms</a></strong>
            </th>
            <td>
                <?= $plugin_wpf ?>
            </td>
            <td>
                <?= __('Plugin for notification from WPForms', WPNE) ?>
            </td>
        </tr>
    </table>
-->
</div>
