<html>
<head></head>
<body>

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
                    admin BOOLEAN, 
                    login TEXT, 
                    password TEXT,
                    validity BOOLEAN)");

    /**************************************
     * Set initial data                    *
     **************************************/

    // Array with some test data to insert to database
    $user = array(
        array('admin' => true,
            'login' => 'admin',
            'password' => 'passw0rd',
            'validity' => true),
        array('chris' => false,
            'login' => 'chris',
            'password' => 'password',
            'validity' => true),
    );


    /**************************************
     * Play with databases and tables      *
     **************************************/

    foreach ($user as $m) {
        $file_db->exec("INSERT INTO collaborators (admin, login, password, validity) 
                VALUES ('{$m['admin']}', '{$m['login']}', '{$m['password']}', '{$m['validity']}')");
    }

    $result = $file_db->query('SELECT * FROM collaborators');

    foreach ($result as $row) {
        echo "Id: " . $row['id'] . "<br/>";
        echo "Admin: " . $row['admin'] . "<br/>";
        echo "Login: " . $row['login'] . "<br/>";
        echo "Password: " . $row['password'] . "<br/>";
        echo "Validity: " . $row['validity'] . "<br/>";
        echo "<br/>";
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
?>
</body>
</html>
