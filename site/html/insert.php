<?php

    try{
        // Create (connect to) SQLite database in file
        $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $title = $_POST["title"];
        $message = $_POST["message"];
        $time = date('Y-m-d H:i:s');

        $file_db->exec(" INSERT INTO messages (title, content, time_value) VALUES ('$title', '$message', '$time');");

        echo "Insertion of message in DB done: ";
        echo "$title";
        echo "$message";
        echo "$time";
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }


    unset($pdo);
?>