<?php
ob_start();
session_start();
?>
<html>
<head>
    <title>Change Password</title>
</head>
<body>
<?php
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    include("header.php");
    include('redirect.php');
    if (isset($_POST['Modifiy']) && !empty($_POST['password-modifier']) && !empty($_POST['password-modifier_repeat'])){
        $password = password_hash($_POST['password-modifier'],   PASSWORD_BCRYPT) ;
        $username = $_SESSION['username'];

        if(password_verify($_POST['password-modifier_repeat'], $password)){
            try{
                $query=$file_db->query("UPDATE collaborators SET password='$password' WHERE `login`='$username';");
            }catch (Exception $e){
                echo 'Oups! something went  wrong';
            }
        }else{
            echo "The two Password are different";
        }

    }
?>
<h2>Change Password</h2>
<form class="form-signin" role="form"
      action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
      ?>" method="post">
    <input type="password" class="form-control"
           name="password-modifier" placeholder="Password">
    <input type="password" class="form-control"
           name="password-modifier_repeat" placeholder="Repeat Password">
    <button class="btn" type="Modifiy" name="Modifiy">Modifiy</button>
</form>
</body>
</html>
