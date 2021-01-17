<?php

use App\Auth;
use App\CsrfManager;
use App\Database;
use App\Flash;
use App\Roles;

require 'includes.php';

Auth::check(Roles::ADMIN);

$pdo = Database::getInstance()->getPdo();

if (isset($_GET['delete'])) {
    if (isset($_GET['token']) && CsrfManager::checkToken($_GET['token'])) {
        $id = $_GET['delete'];

        // On ne peut pas se supprimer soi-même.
        if ($id !== $_SESSION['user']['id']) {
            $req = $pdo->prepare('DELETE FROM collaborators WHERE id = ?');
            $result = $req->execute([$id]);
            if ($result) {
                Flash::success('User deleted');
            } else {
                Flash::error('An error occured while deleting the user, please try again.');
            }
        } else {
            Flash::error('Good try but you cannot delete yourself');
        }
        header('Location: admin.php');
        die();
    } else {
        Flash::error('Invalid CSRF token');
        header('Location: admin.php');
        die();
    }
}

if (!empty($_POST)) {
    if (isset($_POST['token']) && CsrfManager::checkToken($_POST['token'])) {
        if (isset($_POST['username'])
            && !empty($_POST['username'])
            && isset($_POST['password'])
            && !empty($_POST['password'])
            && isset($_POST['role'])
            && isset($_POST['validity'])) {
            $username = addslashes($_POST['username']);
            $password = $_POST['password'];
            $role = $_POST['role'];
            $validity = $_POST['validity'];

            if (($role == Roles::ADMIN || $role == Roles::COLLABORATOR) && ($validity == 0 || $validity == 1)) {
                $req = $pdo->prepare("SELECT COUNT(*) FROM collaborators WHERE login = ?");
                $req->execute([$username]);
                $count = $req->fetchColumn();

                // On check si l'utilisateur existe déjà
                if ($count === '0') {
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
            } else {
                Flash::error('Invalid role or validity.');
            }
        } else {
            Flash::error('Please provide all information to create a new user.');
        }
    } else {
        Flash::error('Invalid CSRF token');
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
                        <th scope="col">Is admin</th>
                        <th scope="col">Is active</th>
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
                            <td><?= $user['admin'] ? '<i class="bi bi-check fs-3 text-success"></i>': '<i class="bi bi-x fs-3 text-danger"></i>' ?></td>
                            <td><?= $user['validity'] ? '<i class="bi bi-check fs-3 text-success"></i>': '<i class="bi bi-x fs-3 text-danger"></i>' ?></td>
                            <td>
                                <a href='edit_user.php?id=<?= $user['id'] ?>' class='btn btn-primary btn-sm'>Edit</a>
                                <?php if ($user['id'] !== $_SESSION['user']['id']): ?>
                                    <a href="admin.php?delete=<?= $user['id'] ?>&token=<?= CsrfManager::getToken() ?>"
                                       class='btn btn-danger btn-sm'
                                       onclick="return confirm('Are your sure you want to delete this user ?\nThere is no rollback possible after this action')">&times;</a>
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
                    <input type="hidden" name="token" value="<?= CsrfManager::getToken() ?>">
                    <button class="btn btn-primary" type="submit">Create</button>
                </form>
            </div>
        </div>
    </div>
<?php
include 'parts/footer.php';
