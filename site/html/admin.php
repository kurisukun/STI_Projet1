<?php
ob_start();
session_start();
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
        !empty($_POST['password'])&&
        !empty($_POST['validity'])&&
        !empty($_POST['role'])) {
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
            $var = password_hash($_POST['role'],   PASSWORD_BCRYPT) ;
            $file_db->exec("INSERT INTO collaborators (admin, login, password, validity)
                        VALUES ('{$_POST['admin']}',
                        '{$_POST['username']}',
                        '{ $var }',
                        '{$_POST['validity']}')");
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
    <input type="text" class="form-control"
           name="role" placeholder="Role" required>
    <input type="text" class="form-control"
           name="username" placeholder="Username" required>
    <input type="password" class="form-control"
           name="password" placeholder="Password" required>
    <input type="text" class="form-control"
           name="validity" placeholder="Validity" required>
    <button class="btn" type="submit" name="submit">Submit</button>
</form>
<h3>Search an User</h3>
<form class="form-signin" role="form"
      action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
      ?>" method="post">
    <input type="text" class="form-control"
           name="username-search" placeholder="Username" required>
   <button class="btn" type="search" name="search">Search</button>
</form>
<?php
if (isset($_POST['search']) && !empty($_POST['username'])) {
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
    $var = password_hash($_POST['role'],   PASSWORD_BCRYPT) ;
    $file_db->exec("UPDATE collaborators SET ");
    } else {
        echo "<br/>";
        echo 'Rule not respected';
    }
}
?>
<h2>Modify a user</h2>

</body>
</html>
