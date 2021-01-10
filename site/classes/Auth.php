<?php


namespace App;

class Auth {

    public static function check($role = Roles::COLLABORATOR) {
        if(!isset($_SESSION['user']) || empty($_SESSION['user'] || is_null($_SESSION['user']))) {
            header('Location: login.php');
            die();
        }

        // Un collaborateur ne peut accéder si un rôle 'admin' est requis
        if($_SESSION['user']['admin'] == 0 && $role == Roles::ADMIN) {
            header('Location: login.php');
            die();
        }
    }

}