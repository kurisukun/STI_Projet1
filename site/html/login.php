<?php
ob_start();
session_start();
?>

<html lang="fr">
<title>
    Page de login
</title>

<head>
    <title>Sti_project</title>
 
</head>

<?php  include("header.php");?>

<h2>Enter Username and Password</h2>
<div class="container form-signin">
    <?php


    // Set default timezone
    date_default_timezone_set('UTC');

    /**************************************
     * Create databases and                *
     * open connections                    *
     **************************************/

    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);

    //$result = $file_db->query('SELECT * FROM collaborators');
    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];

        try{
            $row=$file_db->query("SELECT COUNT(*) as count FROM collaborators WHERE `login`='$username'")->fetch();
            $password_db=$file_db->query("SELECT password,validity FROM collaborators WHERE `login`='$username'")->fetch();
        } catch (Exception $e) {}

        $count=$row['count'];
        if ($count > 0 && password_verify($_POST['password'], $password_db['password']) && $password_db['validity'] > 0) {
            $row = '';
            try{
                $row=$file_db->query("SELECT * FROM collaborators WHERE `login`='$username'")->fetch();
            }catch (Exception $e) {}

            $_SESSION['username'] = $username;
            if($row['admin'] == 1){
                $_SESSION['admin'] = $row['admin'];
            }

            header('Location: list_messages.php');
        } else {
            echo "<br/>";
            echo 'Wrong username or password';
        }
    }
    ?>


</div> <!-- /container -->

<div class="container">

    <form class="form-signin" role="form"
          action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
          ?>" method="post">
        <input type="text" class="form-control"
               name="username" placeholder="username = admin"
               required autofocus></br>
        <input type="password" class="form-control"
               name="password" placeholder="password = passw0rd" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit"
                name="login">Login
        </button>
    </form>
</div>

</body>
</html>
