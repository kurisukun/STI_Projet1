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

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];

    // récupère l'utilisateur s'il existe dans la db
    $req = $pdo->prepare("SELECT * FROM collaborators WHERE login=:username");
    $req->execute(['username' => $username]);
    $data = $req->fetch();
    // récupère les données correspondantes

    // si l'utilisateur n'existe pas ou que les mdp ou qu'il est invalide sont pas correct on refuse la connexion
    if($data) {
        if (password_verify($_POST['password'], $data['password']) && $data['validity'] === '1') {
            $_SESSION['user'] = $data;
            header('Location: list_messages.php');
            die();
        } else {
            echo "<div class='m-3 d-flex align-items-center justify-content-center'>
                    <div class='alert alert-danger'>Wrong username or password.</div>
                </div>";
        }
    }
}

include 'parts/header.php';
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h1>Login</h1>
                <hr>
                <p class="text-muted">Enter Username and Password</p>
                <form role="form" action="login.php" method="post">
                    <div class="mb-3">
                        <input type="text" class="form-control"
                               name="username" placeholder="Username"
                               required autofocus>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control"
                               name="password" placeholder="Password" required>
                    </div>
                    <button class="btn btn-lg btn-primary d-block mx-auto" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
<?php include 'parts/footer.php';
