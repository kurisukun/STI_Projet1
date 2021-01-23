<?php

use App\Auth;
use App\CsrfManager;
use App\Database;
use App\Flash;
use App\Roles;

require 'includes.php';

Auth::check(Roles::ADMIN);

$roles = [
    'Collaborator',
    'Admin'
];

$validities = [
    'Not active',
    'Active'
];

$pdo = Database::getInstance()->getPdo();

if (!empty($_POST)) {
    if (isset($_POST['token']) && CsrfManager::checkToken($_POST['token'])) {
        if (isset($_POST['username'])
            && !empty($_POST['username'])
            && isset($_POST['role'])
            && isset($_POST['id'])
            && isset($_POST['validity'])) {
            $username = addslashes($_POST['username']);
            $password = $_POST['password'] ?? null;
            $role = $_POST['role'];
            $id = $_POST['id'];
            $validity = $_POST['validity'];

            if (($role == Roles::ADMIN || $role == Roles::COLLABORATOR) && ($validity == 0 || $validity == 1)) {
                $req = $pdo->prepare("SELECT COUNT(*) FROM collaborators WHERE id = ?");
                $req->execute([$id]);
                $count = $req->fetchColumn();

                // On check si l'utilisateur existe
                if ($count === '1') {
                    if($password) {
                        $passwordHashed = password_hash($password, PASSWORD_BCRYPT);
                        $req = $pdo->prepare('UPDATE collaborators  SET admin=?, login=?, password=?, validity=? WHERE id=?');
                        $result = $req->execute([
                            $role,
                            $username,
                            $passwordHashed,
                            $validity,
                            $id
                        ]);
                    } else {
                        $req = $pdo->prepare('UPDATE collaborators  SET admin=?, login=?, validity=? WHERE id=?');
                        $result = $req->execute([
                            $role,
                            $username,
                            $validity,
                            $id
                        ]);
                    }

                    if ($result) {
                        Flash::success('User successfully updated.');
                        header('Location: admin.php');
                        die();
                    } else {
                        Flash::error('An error occured while updating the user.');
                    }
                } else {
                    Flash::error("The user doesn't exist.");
                }
            } else {
                Flash::error('Invalid role or validity.');
            }
        } else {
            Flash::error('Please provide username, role and validity to update the user.');
        }
    } else {
        Flash::error('Invalid CSRF token');
    }
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $req = $pdo->prepare('SELECT * FROM collaborators WHERE id = ?');
    $req->execute([$id]);
    $user = $req->fetch();
} else {
    Flash::error('No user id provided');
    header('Location: admin.php');
    die();
}


include 'parts/header.php';
?>
    <div class="container">
        <h1>Edit <?= $user['login'] ?></h1>
        <hr>
        <form method="post">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" value="<?= $user['login'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Leave empty to keep the password unchanged">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control">
                            <?php foreach ($roles as $index => $role): ?>
                                <option <?= $user['admin'] == $index ? 'selected': '' ?> value="<?= $index ?>"><?= $role ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="validity" class="form-label">Validity</label>
                        <select name="validity" id="validity" class="form-control">
                            <?php foreach ($validities as $index => $validity): ?>
                                <option <?= $user['validity'] == $index ? 'selected': '' ?> value="<?= $index ?>"><?= $validity ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="token" value="<?= CsrfManager::getToken() ?>">
                    <div class="d-flex justify-content-between">
                        <button type="submit" role="button" class="btn btn-primary">Update</button>
                        <a href="admin.php">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php
include 'parts/footer.php';