<?php

use App\Database;

require 'includes.php';

$pdo = Database::getInstance()->getPdo();


$req = $pdo->prepare("SELECT id, login, admin, validity FROM collaborators");
$req->execute();
$users = $req->fetchAll();

include 'parts/header.php';

/**************************************
 * section listing des users          *
 **************************************/
?>
    <div class="container">
        <div class="card m-3">
            <div class="card-body">
                <h3 class="card-title">List all User</h3>

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">isAdmin</th>
                        <th scope="col">isValid</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($users as $user): ?>
                        <tr>
                            <th scope='row'><?= $user['id'] ?></th>
                            <td>
                                <?= $user['login'] ?> <?= $user['login'] === $_SESSION['user']['login'] ? '<span class="badge rounded-pill bg-primary">It\'s you</span>' : '' ?>
                            </td>
                            <td><?= $user['admin'] ?></td>
                            <td><?= $user['validity'] ?></td>
                            <td>
                                <a href='admin.php?delete=<?= $user['id'] ?>' class='btn btn-primary btn-sm'>Edit</a>
                                <?php if ($user['id'] !== $_SESSION['user']['id']): ?>
                                    <a href='admin.php?edit=<?= $user['id'] ?>'
                                       class='btn btn-danger btn-sm'>&times;</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
/**************************************
 * section ajout de user              *
 **************************************/
// fonctionne uniquement si le nom d'utilisateur et le mdp sont entré le reste sera mis par défaut
if (isset($_POST['submit']) &&
    !empty($_POST['username']) &&
    !empty($_POST['password'])) {

    $username = $_POST['username'];
    $row = '';
    try {
        $row = $pdo->query("SELECT COUNT(*) as count FROM collaborators WHERE `login`='$username'")->fetch();
    } catch (Exception $e) {
    }

    // on check si l'utilisateur existe déjà
    if ($row['count'] == 0) {
        $var = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $request_begin = "INSERT INTO collaborators (";
        $request_values = ") VALUES (";

        // si le role admin est set on ajoute les données correspondantes
        if (!empty($_POST['role'])) {
            $request_begin = $request_begin . "admin,";
            $request_values = $request_values . $_POST['role'] . ",";
        }

        $request_begin = $request_begin . " login, password";
        $request_values = $request_values . "'" . $_POST['username'] . "' ,'" . $var . "'";

        // si l'utilisateur est valide on ajoute les données correspondantes
        if (!empty($_POST['validity'])) {
            $request_begin = $request_begin . ", validity";
            $request_values = $request_values . " ," . $_POST['validity'];
        }

        $request_values = $request_values . ")";
        try {
            // insertion dans la Db
            $pdo->exec($request_begin . $request_values);
        } catch (PDOException $e) {
        }
        header("Refresh: 0");
    }
}
?>
    <div class="container">
        <div class="card m-3">
            <div class="card-body">
                <h3 class="card-title"> Add new user</h3>
                <form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                      method="post">
                    <div class="form-group">
                        <label for="role"> Role </label>
                        <select class="custom-select" name="role">
                            <option value="0" selected>Collaborator</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="username"> Username </label>
                        <input type="text" class="form-control"
                               name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label for="password"> Password </label>
                        <input type="password" class="form-control"
                               name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="validity"> Validity </label>
                        <select class="custom-select" name="role">
                            <option value="0">Not active</option>
                            <option value="1" selected>Active</option>
                        </select>
                    </div>
                    <button class="btn" type="submit" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card m-3">
            <div class="card-body">
                <h3 class="card-title"> Modify a user </h3>

                <?php
                /**************************************
                 * section modification de user       *
                 **************************************/

                if (isset($_POST['Modifiy'])) {
                    // préparation des attributs dans des variables locales
                    $username = $_POST['username-modifier'];
                    $role = $_POST['role-modifier'];
                    $password = $var = password_hash($_POST['password-modifier'], PASSWORD_BCRYPT);
                    $validity = $_POST['validity-modifier'];
                    try {
                        // pourchaque attributs on test s'il doit être changé et on fait une requête indépendante
                        if (!empty($_POST['role-modifier'])) {
                            $query = $pdo->query("UPDATE collaborators SET admin='$role' WHERE `login`='$username';");
                        }

                        if (!empty($_POST['password-modifier'])) {
                            $query = $pdo->query("UPDATE collaborators SET password='$password' WHERE `login`='$username';");
                        }

                        if (!empty($_POST['validity-modifier'])) {
                            $query = $pdo->query("UPDATE collaborators SET validity='$validity' WHERE `login`='$username';");
                        }
                    } catch (Exception $e) {
                        // affichage d'une erreur si une erreur survient
                        echo "  <div class='m-3 d-flex align-items-center justify-content-center'>
                    <div class='alert alert-danger'>An error occured. Contact your magnificient administrator.</div>
                </div>";
                    }

                    header("Refresh:0");
                }
                ?>

                <form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                      method="post">

                    <div class="form-group">
                        <input type="text" class="form-control" name="username-modifier"
                               placeholder="Username you want to modify" required>
                    </div>
                    <div class="form-group">
                        <select class="custom-select" name="role-modifier">
                            <option value="0">Collaborator</option>
                            <option value="1" selected>Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password-modifier" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <select class="custom-select" name="validity-modifier">
                            <option value="0">Not active</option>
                            <option value="1" selected>Active</option>
                        </select>
                    </div>
                    <button class="btn" type="Modifiy" name="Modifiy">Modifiy</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card m-3">
            <div class="card-body">
                <h3 class="card-title"> Delete an User </h3>
                <form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                      method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="username-delete" placeholder="Username" required>
                    </div>
                    <button class="btn" type="Delete" name="Delete">Delete</button>
                </form>
                <?php
                /**************************************
                 * section suppression de user        *
                 **************************************/
                if (isset($_POST['Delete']) && !empty($_POST['username-delete'])) {
                    $username = $_POST['username-delete'];
                    try {
                        // suppression de l'utilisateur dans la Db
                        $pdo->query("DELETE FROM collaborators WHERE `login`='$username'");
                    } catch (Exception $e) {
                    }

                    header("Refresh: 0");
                }
                ?>
            </div>
        </div>
    </div>
<?php
include 'parts/footer.php';
