<?php
require 'includes.php';

unset($_SESSION['user']);
\App\Flash::success("You're successfully logged out");
header('Location: login.php');
die();
