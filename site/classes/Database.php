<?php

namespace App;

use PDO;

class Database {

    private static ?Database $instance = null;

    private PDO $pdo;

    /**
     * Database constructor.
     */
    private function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=database;dbname=sti', 'sti', 'sti');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if(is_null(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }


}