<?php

use App\Database;

require 'includes.php';

// Set default timezone
date_default_timezone_set('UTC');

/**************************************
 * Create databases and                *
 * open connections                    *
 **************************************/

// Create (connect to) SQLite database in file
$pdo = Database::getInstance()->getPdo();

if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];

    try {
        // récupère l'utilisateur s'il existe dans la db
        $row = $pdo->query("SELECT COUNT(*) as count FROM collaborators WHERE `login`='$username'")->fetch();
        // récupère les données correspondantes
        $password_db = $pdo->query("SELECT password,validity FROM collaborators WHERE `login`='$username'")->fetch();
    } catch (Exception $e) {
        die($e->getMessage());
    }

    $count = $row['count'];
    // si l'utilisateur n'existe pas ou que les mdp ou qu'il est invalide sont pas correct on refuse la connexion
    if ($count > 0 && password_verify($_POST['password'], $password_db['password']) && $password_db['validity'] > 0) {
        $row = '';
        try {
            // récuère les données pour set la session
            $row = $pdo->query("SELECT * FROM collaborators WHERE `login`='$username'")->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        // set de la session standard
        $_SESSION['username'] = $username;
        // set de la session admin
        if ($row['admin'] == 1) {
            $_SESSION['admin'] = $row['admin'];
        }

        header('Location: list_messages.php');
        die();
    } else {
        echo "<div class='m-3 d-flex align-items-center justify-content-center'>
                    <div class='alert alert-danger'>Wrong username or password.</div>
                </div>";
    }
}

include 'parts/header.php';
?>

    <div class="container">
        <h2>Enter Username and Password</h2>
        <form role="form"
              action="login.php" method="post">
            <input type="text" class="form-control"
                   name="username" placeholder="username"
                   required autofocus></br>
            <input type="password" class="form-control"
                   name="password" placeholder="password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit"
                    name="login">Login
            </button>
        </form>
    </div>

<?php include 'parts/footer.php';
