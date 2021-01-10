<?php

use App\Database;

require 'includes.php';

include 'parts/header.php';
// Create (connect to) SQLite database in file
$pdo = Database::getInstance()->getPdo();
// inclusion du layout de la page et de la redirection en cas de non connexion
include('redirect.php');

if (!empty($_POST['password-modifier']) && !empty($_POST['password-modifier_repeat'])) {
    $password = password_hash($_POST['password-modifier'], PASSWORD_BCRYPT);
    $username = $_SESSION['username'];

    // check si les deux mdp correspondent
    if (password_verify($_POST['password-modifier_repeat'], $password)) {
        try {
            // modification dans la db
            $query = $pdo->query("UPDATE collaborators SET password='$password' WHERE `login`='$username';");
        } catch (Exception $e) {
        }
    } else {
        // affichage message d'erreur
        echo "<div class='m-3 d-flex align-items-center justify-content-center'>
                    <div class='alert alert-danger'>The two password are not the same.</div>
                </div>";
    }
}
?>

<!-- Form for changing password -->
<div class="container">
    <h1>Change Password</h1>
    <hr>
    <form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="mb-3">
            <label for="pass1" class="form-label">New password</label>
            <input type="password" class="form-control" name="password-modifier" id="pass1" placeholder="Password">
        </div>
        <div class="mb-3">
            <label for="pass2" class="form-label">Repeate new password</label>
            <input type="password" class="form-control" name="password-modifier_repeat" id="pass2" placeholder="Repeat Password">
        </div>
        <button class="btn btn-primary" type="submit">Change password</button>
    </form>
</div>

<?php include 'parts/footer.php';
