<?php

namespace Milebits\Eloquent\Filters\Helpers;

if (!function_exists('constant_exist')) {
    /**
     * @param $class
     * @param string $const
     * @return bool
     */
    function constant_exists($class, string $const)
    {
        if (is_object($class))
            $class = get_class($class);
        return defined("$class::$const");
    }
}

if (!function_exists('constant_value')) {
    /**
     * @param $class
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    function constant_value($class, string $name, $default = null)
    {
        if (is_object($class)) $class = get_class($class);
        return constant_exists($class, $name) ? constant("$class::$name") : $default;
    }
}