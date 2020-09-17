<?php

namespace notify_events\helpers;

/**
 * Class Inflector
 * @package notify_events\helpers
 */
class Inflector
{
    /**
     * @param string $id
     * @return string
     */
    public static function class_from_id($id)
    {
        $id = explode('_', $id);

        foreach ($id as $idx => $slug) {
            $id[$idx] = ucfirst($slug);
        }

        return implode('', $id);
    }

    /**
     * @param string $class
     * @return string
     */
    public static function id_from_class($class)
    {
        $class = explode('\\', $class);
        $class = array_pop($class);
        $class = preg_replace('#[A-Z0-9]#', '_$0', $class);
        $class = ltrim($class, '_');
        $class = strtolower($class);

        return $class;
    }

    /**
     * @param string $namespace
     * @return string
     */
    public static function namespace_to_plugin($namespace)
    {
        return str_replace('_', '-', $namespace);
    }
}