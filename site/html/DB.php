<?php

use App\Database;

require 'includes.php';

try {
    /**************************************
     * Create databases and                *
     * open connections                    *
     **************************************/

    $pdo = Database::getInstance()->getPdo();

    /**************************************
     * Create tables                       *
     **************************************/

    // Drop table messages from file db
    $pdo->exec("DROP TABLE IF EXISTS collaborators");

    // Create table messages
    $pdo->exec("CREATE TABLE IF NOT EXISTS collaborators (
                    id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                    admin BOOLEAN DEFAULT FALSE,
                    login TEXT NOT NULL,
                    password TEXT NOT NULL,
                    validity BOOLEAN DEFAULT FALSE)");

    // Drop table messages from file db
    $pdo->exec("DROP TABLE IF EXISTS messages");
    // Create table messages
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
                            id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
                            title VARCHAR(255), 
                            content TEXT, 
                            time_value DATETIME,
                            idExpediteur INTEGER UNSIGNED,
                            idDestinataire INTEGER UNSIGNED)");

    /**************************************
     * Set initial data                    *
     **************************************/

    // Array with some test data to insert to database
    $user = array(
        array('admin' => 1,
            'login' => 'admin',
            'password' => password_hash('admin', PASSWORD_BCRYPT),
            'validity' => 1),
        array('admin' => 0,
            'login' => 'prof',
            'password' => password_hash('prof', PASSWORD_BCRYPT),
            'validity' => 1),
        array('admin' => 0,
            'login' => 'Assistant',
            'password' => password_hash('password', PASSWORD_BCRYPT),
            'validity' => 1),
    );

    // Array with some test data to insert to database
    $messages = array(
        array('title' => 'Hello!',
            'content' => 'Just testing...',
            'time_value' => 1327301464,
            'idExpediteur' => 2,
            'idDestinataire' => 3),
        array('title' => 'Hello again!',
            'content' => 'More testing...',
            'time_value' => 1339428612,
            'idExpediteur' => 2,
            'idDestinataire' => 3),
        array('title' => 'Euh!',
            'content' => 'Hello',
            'time_value' => 1339428718,
            'idExpediteur' => 3,
            'idDestinataire' => 2)
    );

    // Insertion of collaborators and messages 

    foreach ($user as $m) {
        $pdo->exec("INSERT INTO collaborators (admin, login, password, validity)
                VALUES ('{$m['admin']}', '{$m['login']}', '{$m['password']}', '{$m['validity']}')");
    }

    foreach ($messages as $m) {
        $formatted_time = date('Y-m-d H:i:s', $m['time_value']);
        $pdo->exec("INSERT INTO messages (title, content, time_value, idExpediteur, idDestinataire) 
                VALUES ('{$m['title']}',
                        '{$m['content']}', 
                        '{$formatted_time}',
                        '{$m['idExpediteur']}',
                        '{$m['idDestinataire']}')");
    }
} catch (PDOException $e) {
    die($e->getMessage());
}
