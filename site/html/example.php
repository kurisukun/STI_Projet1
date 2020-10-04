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

    // Create table messages
    $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY, 
                    subject TEXT, 
                    message TEXT, 
                    send_date TEXT)");

    /**************************************
     * Set initial data                    *
     **************************************/

    // Array with some test data to insert to database             
    $messages = array(
        array('subject' => 'Hello!',
            'message' => 'Just testing...',
            'send_date' => 1327301464),
        array('subject' => 'Hello again!',
            'message' => 'More testing...',
            'send_date' => 1339428612),
        array('subject' => 'Hi!',
            'message' => 'SQLite3 is cool...',
            'send_date' => 1327214268)
    );


    /**************************************
     * Play with databases and tables      *
     **************************************/

    foreach ($messages as $m) {
        $formatted_time = date('Y-m-d H:i:s', $m['send_date']);
        $file_db->exec("INSERT INTO messages (title, message, time) 
                VALUES ('{$m['subject']}', '{$m['message']}', '{$formatted_time}')");
    }

    $result = $file_db->query('SELECT * FROM messages');

    foreach ($result as $row) {
        echo "Id: " . $row['id'] . "<br/>";
        echo "Subject: " . $row['subject'] . "<br/>";
        echo "Message: " . $row['message'] . "<br/>";
        echo "Send Date: " . $row['send_date'] . "<br/>";
        echo "<br/>";
    }


    /**************************************
     * Drop tables                         *
     **************************************/

    // Drop table messages from file db
    //$file_db->exec("DROP TABLE messages");

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
