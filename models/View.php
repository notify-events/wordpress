<?php

namespace notify_events\models;

use notify_events\helpers\Inflector;

/**
 * Class View
 * @package notify_events
 */
class View
{
    /** @var string */
    protected $_parent_class;

    /** @var string */
    protected $_module_name;

    public function __construct($parent_class, $module_name = null)
    {
        $this->_parent_class = $parent_class;
        $this->_module_name  = $module_name;
    }

    /**
     * @param string      $view_name
     * @param array       $params
     * @return string
     */
    public function internal_render($view_name, $params)
    {
        $parent_class = explode('\\', $this->_parent_class);

        if ($this->_module_name) {
            $module = Core::instance()->module_get($this->_module_name);

            $plugin_name = $module::module_plugin();

            $plugin_name = sprintf('%s/modules/%s', $plugin_name, $module::module_name());
        } else {
            $plugin_name = array_shift($parent_class);
            $plugin_name = Inflector::namespace_to_plugin($plugin_name);
        }

        $view_path = array_pop($parent_class);
        $view_path = Inflector::id_from_class($view_path);

        if (substr($view_path, -11) == '_controller') {
            $view_path = substr($view_path, 0, -11);
        }

        if (substr($view_name, 0, 1) == '/') {
            $view_name = sprintf('%s/%s/views/%s', WP_PLUGIN_DIR, $plugin_name, $view_name);
        } else {
            $view_name = sprintf('%s/%s/views/%s/%s', WP_PLUGIN_DIR, $plugin_name, $view_path, $view_name);
        }

        $view_file_name = $view_name . '.php';

        if (!file_exists($view_file_name)) {
            wp_die(sprintf('View "%s" not found!', esc_html($view_file_name)));
        }

        return $this->execute($view_file_name, $params);
    }

    /**
     * @param string $view_file_name
     * @param array $params
     * @return string
     */
    protected function execute($view_file_name, $params = [])
    {
        extract($params);

        ob_start();

        require($view_file_name);

        return ob_get_clean();
    }

    /**
     * @param string      $view_name
     * @param array       $params
     * @param string|null $module_name
     * @return string
     */
    public function render($view_name, $params = [], $module_name = null)
    {
        $view = new View($this->_parent_class, ($module_name !== null) ? $module_name : $this->_module_name);

        return $view->internal_render($view_name, $params);
    }
}
