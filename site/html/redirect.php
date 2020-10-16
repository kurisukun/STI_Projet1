<?php
// Si le nom d'utilisateur de session n'est pas set on redirige sur la page de login
    if(!isset($_SESSION['username'])) {
        header('Location: login.php');
    }
?>