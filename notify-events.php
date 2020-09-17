<?php
/*
Plugin Name: Notify.Events - Ultimate notification
Plugin URI: https://notify.events
Description: Notify.Events plugin is ultimate tool for any kind of notifications from your WordPress website to more than 20 messengers and platfroms such as SMS, voicecall, Facebook messenger, Viber, Telegram and many more
Author: Notify.Events
Author URI: https://notify.events
Version: 1.0.0
License: GPL-2.0
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: notify-events
Domain Path: /languages/
*/

use notify_events\models\Core;
use notify_events\modules\wordpress\models\WordPress;

const WPNE = 'notify-events';

spl_autoload_register(function($class) {
    if (stripos($class, 'notify_events\\') !== 0) {
        return;
    }

    $class_file = __DIR__ . '/' . str_replace(['notify_events\\', '\\'], ['', '/'], $class . '.php');

    if (!file_exists($class_file)) {
        return;
    }

    require_once $class_file;
});

add_action('user_register', function($user_id) {
    error_log('=== Register: ' . $user_id . ' ===');
}, 10, 1);

Core::instance();

add_action('wpne_module_init', function() {
    WordPress::register();
});
