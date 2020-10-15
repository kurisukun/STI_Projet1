<?php
// Set default timezone
date_default_timezone_set('UTC');

try {
    /**************************************
     * Create databases and                *
     * open connections                    *
     **************************************/

    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);

    /**************************************
     * Create tables                       *
     **************************************/

    // Drop table messages from file db
    $file_db->exec("DROP TABLE IF EXISTS collaborators");

    // Create table messages
    $file_db->exec("CREATE TABLE IF NOT EXISTS collaborators (
                    id INTEGER PRIMARY KEY,
                    admin INTEGER DEFAULT 0,
                    login TEXT NOT NULL ,
                    password TEXT NOT NULL ,
                    validity INTEGER DEFAULT 0)");

            // Drop table messages from file db
            $file_db->exec("DROP TABLE IF EXISTS messages");
            // Create table messages
            $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
                            id INTEGER PRIMARY KEY, 
                            title TEXT, 
                            content TEXT, 
                            time_value DATETIME,
                            idExpediteur INTEGER,
                            idDestinataire INTEGER)");

    /**************************************
     * Set initial data                    *
     **************************************/

    // Array with some test data to insert to database
    $user = array(
        array('admin' => 1,
            'login' => 'admin',
            'password' => password_hash('passw0rd',   PASSWORD_BCRYPT),
            'validity' => 1),
        array('admin' => 0,
            'login' => 'Prof',
            'password' => password_hash('password',   PASSWORD_BCRYPT),
            'validity' => 1),
        array('admin' => 0,
            'login' => 'Assistant',
            'password' => password_hash('password',   PASSWORD_BCRYPT),
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

    /**************************************
     * Play with databases and tables      *
     **************************************/

    foreach ($user as $m) {
        $file_db->exec("INSERT INTO collaborators (admin, login, password, validity)
                VALUES ('{$m['admin']}', '{$m['login']}', '{$m['password']}', '{$m['validity']}')");
    }

    foreach ($messages as $m) {
        $formatted_time = date('Y-m-d H:i:s', $m['time_value']);
        $file_db->exec("INSERT INTO messages (title, content, time_value, idExpediteur, idDestinataire) 
                VALUES ('{$m['title']}',
                        '{$m['content']}', 
                        '{$formatted_time}',
                        '{$m['idExpediteur']}',
                        '{$m['idDestinataire']}')");
    }
} catch (PDOException $e) {
}
header('Refresh: 0; URL = login.php');
?>
