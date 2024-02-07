<?php

namespace Editiel98;

class Autoloader
{
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class)
    {
        //NameSpace Editiel98
        if (strpos($class, __NAMESPACE__ . '\\') === 0) {
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require '../src/Core/' . $class . '.php';
        }
        //NameSpace App
        if (strpos($class, 'App' . '\\') === 0) {
            $class = str_replace('App' . '\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require '../src/App/' . $class . '.php';
        }
    }
}
