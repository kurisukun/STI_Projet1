<?php


namespace App;


class Flash {

    public static function error($message): void {
        self::set($message, 'danger');
    }

    public static function set($message, $type = 'success'): void {
        $_SESSION['flash']['type'] = $type;
        $_SESSION['flash']['message'] = $message;
    }

    public static function display(): void {
        if (isset($_SESSION['flash'])) {
            $type = $_SESSION['flash']['type'];
            $message = $_SESSION['flash']['message'];
            echo '<div class="alert alert-' . $type . '">' . $message . '</div>';
            unset($_SESSION['flash']);
        }
    }

}