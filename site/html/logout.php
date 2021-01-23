<?php

use App\Auth;
use App\CsrfManager;
use App\Flash;

require 'includes.php';

Auth::logout();
CsrfManager::wipeToken();
Flash::success("You're successfully logged out");

header('Location: login.php');
die();
