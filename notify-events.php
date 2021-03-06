<?php
/*
Plugin Name: Notify.Events
Plugin URI: https://notify.events/source/wordpress
Description: Notify.Events plugin is ultimate tool for any kind of notifications from your WordPress website to more than 20 messengers and platfroms such as SMS, voicecall, Facebook messenger, Viber, Telegram and many more
Author: Notify.Events
Author URI: https://notify.events/
Version: 1.2.2
License: GPL-2.0
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: notify-events
Domain Path: /languages/
*/

use notify_events\models\Core;
use notify_events\modules\contact_form_7\models\ContactForm7;
use notify_events\modules\ninja_forms\models\NinjaForms;
use notify_events\modules\woocommerce\models\WooCommerce;
use notify_events\modules\wordpress\models\WordPress;
use notify_events\modules\wpforms\models\WPForms;

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

Core::instance();

add_action('wpne_module_init', function() {
    WordPress::register();

    if (Core::is_plugin_active('woocommerce/woocommerce.php')) {
        WooCommerce::register();
    }

    if (Core::is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
        ContactForm7::register();
    }

    if (Core::is_plugin_active('wpforms-lite/wpforms.php') || Core::is_plugin_active('wpforms/wpforms.php')) {
        WPForms::register();
    }

    if (Core::is_plugin_active('ninja-forms/ninja-forms.php')) {
        NinjaForms::register();
    }
});
