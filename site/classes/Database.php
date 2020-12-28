<?php

namespace App;

class Database {

    private static ?Database $instance = null;

    /**
     * Database constructor.
     */
    private function __construct()
    {
    }

    public static function getInstance(): Database {
        if(is_null(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}