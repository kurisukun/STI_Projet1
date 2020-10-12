<?php
ob_start();
session_start();
include("DB.php")
?>
<html>
<head>
    <title>Gestion des collaborateurs</title>
</head>
<body>
<?php
include("header.html");
include('redirect.php');
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
    if (isset($_POST['submit']) &&
        !empty($_POST['username']) &&
        !empty($_POST['password'])) {

        $username = $_POST['username'];
        $row = array('count' => 1);
        try{
            $query=$file_db->query("SELECT COUNT(*) as count FROM collaborators WHERE `login`='$username'");
            $row = $query->fetch();
        } catch (Exception $e) {
            // Print PDOException message
            echo $e->getMessage();
        }

        if ($row['count'] == 0) {
            $var = password_hash($_POST['password'],   PASSWORD_BCRYPT) ;
            $request_begin = "INSERT INTO collaborators (";
            $request_values = ") VALUES (";

            if(!empty($_POST['role'])){
                $request_begin = $request_begin . "admin,";
                $request_values = $request_values  . $_POST['role'] . ",";
            }

            $request_begin = $request_begin . " login, password";
            $request_values = $request_values  . "'" . $_POST['username'] . "' ,'" . $var. "'";

            if(!empty($_POST['validity'])){
                $request_begin = $request_begin . ", validity";
                $request_values = $request_values  . " ," . $_POST['validity'];
            }

            $request_values = $request_values . ")";

            echo $request_begin . $request_values;

            try{
                $file_db->exec($request_begin . $request_values);
            } catch (PDOException $e) {
                // Print PDOException message
                echo $e->getMessage();
            }
        } else {
            echo "<br/>";
            echo 'Username already taken';
        }
    }
?>
<h2>Add a new user</h2>
<form class="form-signin" role="form"
      action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
      ?>" method="post">
    <input type="number" class="form-control"
           name="role" placeholder="Role" >
    <input type="text" class="form-control"
           name="username" placeholder="Username" required>
    <input type="password" class="form-control"
           name="password" placeholder="Password" required>
    <input type="number" class="form-control"
           name="validity" placeholder="Validity" >
    <button class="btn" type="submit" name="submit">Submit</button>
</form>
<h2>Search an User</h2>
<form class="form-signin" role="form"
      action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
      ?>" method="post">
    <input type="text" class="form-control"
           name="username-search" placeholder="Username" required>
   <button class="btn" type="search" name="search">Search</button>
</form>
<?php
if (isset($_POST['search']) && !empty($_POST['username-search'])) {
    $username = $_POST['username-search'];
    $row = array('count' => 1);
    try{
        $query=$file_db->query("SELECT COUNT(*) as count FROM collaborators WHERE `login`='$username'");
        $row = $query->fetch();
    } catch (Exception $e) {
        // Print PDOException message
        echo $e->getMessage();
    }

    if ($row['count'] == 1) {
        echo "<br/>";
        echo 'It exist!';
    } else {
        echo "<br/>";
        echo 'Sorry, Try again.';
    }
}
?>
<h2>Modify a user</h2>
<?php
    if (isset($_POST['Modifiy'])){
        $username = $_POST['username-modifier'];
        $role=$_POST['role-modifier'];
        $password=$var = password_hash($_POST['password-modifier'],   PASSWORD_BCRYPT) ;
        $validity=$_POST['validity-modifier'];
        if(!empty($_POST['role-modifier'])){
            $query=$file_db->query("UPDATE collaborators SET admin='$role' WHERE `login`='$username';");
        }

        if(!empty($_POST['password-modifier'])){
            $query=$file_db->query("UPDATE collaborators SET password='$password' WHERE `login`='$username';");
        }

        if(!empty($_POST['validity-modifier'])){
            $query=$file_db->query("UPDATE collaborators SET validity='$validity' WHERE `login`='$username';");
        }
    }
?>
<form class="form-signin" role="form"
      action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
      ?>" method="post">
    <input type="text" class="form-control"
           name="username-modifier" placeholder="Username you want to modify" required>
    <input type="text" class="form-control"
           name="role-modifier" placeholder="Role">
    <input type="password" class="form-control"
           name="password-modifier" placeholder="Password">
    <input type="text" class="form-control"
           name="validity-modifier" placeholder="Validity">
    <button class="btn" type="Modifiy" name="Modifiy">Modifiy</button>
</form>
</body>
</html>
