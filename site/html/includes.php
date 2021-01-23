<?php

use App\CsrfManager;

session_start();

require dirname(__DIR__) . '/vendor/autoload.php';

CsrfManager::generateToken();
