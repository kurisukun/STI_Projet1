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
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ADABAB;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            color: #017572;
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }

        .form-signin .checkbox {
            font-weight: normal;
        }

        .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color: #017572;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color: #017572;
        }

        h2 {
            text-align: center;
            color: #017572;
        }
    </style>

</head>

<body>

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
    $msg = '';

    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $row = array('count' => 1);
        try{
            $query=$file_db->query("SELECT COUNT(*) as count FROM collaborators WHERE `login`='$username' AND `password`='$password'");
            $row = $query->fetch();
        } catch (Exception $e) {
            // Print PDOException message
            echo $e->getMessage();
        }

        $count=$row['count'];
        if ($count == 1) {
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['admin'] = true;


            echo 'You have entered valid use name and password';
            header('Refresh: 0; URL = message.php');
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
        <h4 class="form-signin-heading"><?php echo $msg; ?></h4>
        <input type="text" class="form-control"
               name="username" placeholder="username = sti_admin"
               required autofocus></br>
        <input type="password" class="form-control"
               name="password" placeholder="password = passw0rd" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit"
                name="login">Login
        </button>
    </form>

    Click here to <a href="logout.php" tite="Logout">Log out.

</div>

</body>
</html>
