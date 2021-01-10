<?php


namespace App;

class Auth {

    public static function check($role = Roles::COLLABORATOR) {
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            Flash::error('Please login before using the app.');
            header('Location: login.php');
            die();
        }

        // Un collaborateur ne peut accéder si un rôle 'admin' est requis
        if($_SESSION['user']['admin'] == 0 && $role == Roles::ADMIN) {
            Flash::error('Unauthorized access');
            header('Location: list_messages.php');
            die();
        }
    }

}