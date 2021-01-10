<?php

use App\Auth;
use App\Database;
use App\Flash;
use App\Roles;

require 'includes.php';

Auth::check(Roles::ADMIN);

$pdo = Database::getInstance()->getPdo();

if (!empty($_POST)) {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role']) && isset($_POST['validity'])) {
        $username = addslashes($_POST['username']);
        $password = $_POST['password'];
        $role = $_POST['role'];
        $validity = $_POST['validity'];

        if (($role == Roles::ADMIN || $role == Roles::COLLABORATOR) && ($validity == 0 || $validity == 1)) {
            $req = $pdo->prepare("SELECT COUNT(*) as count FROM collaborators WHERE login='$username'");
            $req->execute();
            $count = $req->fetchColumn();

            // On check si l'utilisateur existe déjà
            if ($count == 0) {
                $passwordHashed = password_hash($password, PASSWORD_BCRYPT);
                $req = $pdo->prepare('INSERT INTO collaborators (admin, login, password, validity) VALUES (?, ?, ?, ?)');
                $result = $req->execute([
                    $role,
                    $username,
                    $passwordHashed,
                    $validity
                ]);

                if ($result) {
                    Flash::success('User successfully created.');
                } else {
                    Flash::error('An error occured while creating the user.');
                }
            } else {
                Flash::error('Username already taken, please choose another username.');
            }
        }
    } else {
        Flash::error('Please provide all information to create a new user.');
    }
}


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
        <?php
        /**************************************
         * section ajout de user              *
         **************************************/
        // fonctionne uniquement si le nom d'utilisateur et le mdp sont entré le reste sera mis par défaut

        ?>
        <div class="card m-3">
            <div class="card-body">
                <h3 class="card-title">Add new user</h3>
                <form action="admin.php" method="post">
                    <div class="mb-3 row">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username"
                                   name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password"
                                   name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="role" class="col-sm-2 col-form-label">Role</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="role" id="role">
                                <option value="0" selected>Collaborator</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="validity" class="col-sm-2 col-form-label">Validity</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="validity" id="validity">
                                <option value="0">Not active</option>
                                <option value="1" selected>Active</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Create</button>
                </form>
            </div>
        </div>

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
