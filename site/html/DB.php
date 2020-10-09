<?php
function initialize(){
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
                        admin BOOLEAN,
                        login TEXT,
                        password TEXT,
                        validity BOOLEAN)");

        // Drop table messages from file db
        $file_db->exec("DROP TABLE IF EXISTS messages");
        // Create table messages
        $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
                        id INTEGER PRIMARY KEY, 
                        title TEXT, 
                        content TEXT, 
                        time_value TEXT)");

        /**************************************
         * Set initial data                    *
         **************************************/

        // Array with some test data to insert to database
        $user = array(
            array('admin' => true,
                'login' => 'admin',
                'password' => password_hash('passw0rd',   PASSWORD_BCRYPT),
                'validity' => true),
            array('chris' => false,
                'login' => 'chris',
                'password' => password_hash('password',   PASSWORD_BCRYPT),
                'validity' => true),
        );

        // Array with some test data to insert to database
        $messages = array(
            array('title' => 'Hello!',
                'content' => 'Just testing...',
                'time_value' => 1327301464),
            array('title' => 'Hello again!',
                'content' => 'More testing...',
                'time_value' => 1339428612),
            array('title' => 'Hi!',
                'content' => 'SQLite3 is cool...',
                'time_value' => 1327214268)
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
            $file_db->exec("INSERT INTO messages (title, content, time_value) 
                    VALUES ('{$m['title']}', '{$m['content']}', '{$formatted_time}')");
        }

        /**************************************
         * Drop tables                         *
         **************************************/

        // Drop table messages from file db
        //$file_db->exec("DROP TABLE collaborators");

        /**************************************
         * Close db connections                *
         **************************************/

        // Close file db connection
        //$file_db = null;
    } catch (PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
    }
}
?>