<?php
ob_start();
session_start();
?>

<html>
<head>
    <title>
        Gestion des messages
    </title>
</head>
<body>
<?php include("header.html")?>
<?php
include 'redirect.php'
?>
<form action="index.php" method="get">
    <input type="text" name="user"/>
    <input type="submit" value="Envoyer"/>
</form>
</body>
</html>
