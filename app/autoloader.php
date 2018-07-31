<?php

namespace app;

/**
 * Description of autoloader
 *
 * @author steph
 */

class autoloader {
    
    static function register() {
        spl_autoload_register(array(__CLASS__, 'load'));
    }
    
    static function load($class_name) {
        $class_name = str_replace(__NAMESPACE__, '', $class_name);
        $class_name = str_replace('\\', '/', $class_name);
        require $class_name . '.php';
    }
    
}
