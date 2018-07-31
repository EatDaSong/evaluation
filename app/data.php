<?php

namespace app;

/**
 * Description of app
 *
 * @author steph
 */
class data {
    
    //Config BDD
    
    const DB_name = 'studione';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_HOST = 'localhost';
    
    private static $database;
    
    public static function getDB(){
        if(self::$database === null) {
            self::$database = new database(self::DB_name, self::DB_USER, self::DB_PASS, self::DB_HOST);
        }
        return self::$database;
    }
    
}
