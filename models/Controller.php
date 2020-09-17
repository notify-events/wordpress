<?php

namespace notify_events\models;

/**
 * Class Controller
 * @package notify_events\models
 */
abstract class Controller
{
    public $layout = 'default';

    /**
     * @param string $action_name
     * @return string
     */
    public function do_action($action_name)
    {
        $action_name = 'action_' . $action_name;

        if (!method_exists($this, $action_name)) {
            wp_die(sprintf('Action "%s" not found!', esc_html($action_name)));
        }

        return call_user_func([$this, $action_name]);
    }

    /**
     * @param string      $view_name
     * @param array       $params
     * @param string|null $module_name
     * @return string
     */
    protected function render($view_name, $params = [], $module_name = null)
    {
        $layout = new View(static::class);
        $view   = new View(static::class, $module_name);

        return $layout->internal_render('/layout/' . $this->layout, [
            'controller' => $this,
            'content'    => $view->internal_render($view_name, $params),
        ]);
    }
}