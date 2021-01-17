<?php


namespace App;

use Exception;

class CsrfManager {

    const KEY = 'csrf_token';

    public static function generateToken() {
        if(!isset($_SESSION[self::KEY])) {
            try {
                $_SESSION[self::KEY] = bin2hex(random_bytes(16));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }

    public static function getToken() {
        if(!isset($_SESSION[self::KEY])) {
            self::generateToken();
        }
        return $_SESSION[self::KEY];
    }

    public static function checkToken($token): bool {
        return hash_equals(self::getToken(), $token);
    }

    public static function wipeToken() {
        if(isset($_SESSION[self::KEY])) {
            unset($_SESSION[self::KEY]);
        }
    }
}