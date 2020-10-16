<?php
// inclusion du layout de la page et de la redirection en cas de non connexion
include("header.php");
include('redirect.php');
session_start();
// on enlève toutes données de session et on redirige sur la page login
unset($_SESSION["username"]);
unset($_SESSION["admin"]);

header('URL = login.php');
?>
