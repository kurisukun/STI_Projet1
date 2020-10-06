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
?>
<form class="form-signin" role="form"
      action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
      ?>" method="post">
    <input type="text" class="form-control"
           name="username" placeholder="Role" required>
    <input type="password" class="form-control"
           name="password" placeholder="Username" required>
    <input type="password" class="form-control"
           name="password" placeholder="Passw0rd" required>
    <input type="password" class="form-control"
           name="password" placeholder="Validity" required>
    <button class="btn" type="submit" name="submit">Login</button>
</form>

</body>
</html>
