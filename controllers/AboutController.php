<?php

namespace notify_events\controllers;

use notify_events\helpers\Badge;
use notify_events\models\Controller;

/**
 * Class AboutController
 * @package notify_events\controllers
 */
class AboutController extends Controller
{
    /**
     * @param string|string[] $plugin
     * @param string          $ne_plugin
     * @return string
     */
    protected static function plugin_badge($plugin, $ne_plugin)
    {
        if (is_array($plugin)) {
            $plugin_active = false;

            foreach ($plugin as $plug) {
                if (is_plugin_active($plug)) {
                    $plugin_active = true;
                    break;
                }
            }
        } else {
            $plugin_active = is_plugin_active($plugin);
        }

        $plugin_ne_active = is_plugin_active($ne_plugin);

        if ($plugin_ne_active) {
            return Badge::success(__('Already installed', WPNE));
        } elseif ($plugin_active) {
            return Badge::warning(__('Recommended', WPNE));
        } else {
            return Badge::primary(__('Available', WPNE));
        }
    }

    /**
     * @return string
     */
    public function action_index()
    {
        $plugin_woo = self::plugin_badge('woocommerce/woocommerce.php', 'notify-events-woocommerce/notify-events-woocommerce.php');
        $plugin_cf7 = self::plugin_badge('contact-form-7/wp-contact-form-7.php', 'notify-events-contact-form-7/notify-events-contact-form-7.php');
        $plugin_wpf = self::plugin_badge(['wpforms-lite/wpforms.php', 'wpforms/wpforms.php'], 'notify-events-wpforms/notify-events-wpforms.php');
        $plugin_njf = self::plugin_badge('ninja-forms/ninja-forms.php', 'notify-events-ninja-forms/notify-events-ninja-forms.php');

        return $this->render('index', [
            'plugin_woo' => $plugin_woo,
            'plugin_cf7' => $plugin_cf7,
            'plugin_wpf' => $plugin_wpf,
            'plugin_njf' => $plugin_njf,
        ]);
    }
}