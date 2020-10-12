<?php
ob_start();
session_start();
?>

<?php

    try{
        // Create (connect to) SQLite database in file
        $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $title = $_POST["title"];
        $message = $_POST["message"];
        $time = date('Y-m-d H:i:s');
        $sender = $_SESSION['username'];
        $sender_id = $file_db->exec(" SELECT id FROM collaborators WHERE login = '$sender';");
        $receiver = $_POST['contact'];
        $receiver_id = $file_db->exec(" SELECT id FROM collaborators WHERE login = '$receiver';");
        $file_db->exec(" INSERT INTO messages (title, content, time_value, idExpediteur, idDestinataire) VALUES ('$title', '$message', '$time', '$sender_id', '$receiver_id');");

        echo "Insertion of message in DB done: ";
        echo "$title";
        echo "$message";
        echo "$time";
        echo "$sender_id";
        echo "$receiver_id";
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }


    unset($pdo);
?>