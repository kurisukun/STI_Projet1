<?php
ob_start();
session_start();
include("header.php");
include("redirect.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Message</title>
</head>
<body>

<div class="container">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title"/>
        </div>
        <div class="form-group col-md-6">
            <label for="contact">Contact</label>
            <input type="text" class="form-control" name="contact" id="contact"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="message">Message</label>
        <textarea class="form-control" name="message" id="message"></textarea>
    </div>
    <div>
        <input type="submit" class="form-control" value="Envoyer" />
    </div>
</form>
</div>


</body>
</html>


<?php

if (!empty($_POST['title']) && !empty($_POST['contact'])){
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $title = $_POST["title"];
    $message = $_POST["message"];
    $time = date('Y-m-d H:i:s');
    $sender = $_SESSION['username'];
    $sender_query = $file_db->query("SELECT id FROM collaborators WHERE `login`='$sender';")->fetch();
    //$sender_id = $file_db->exec(" SELECT id FROM collaborators WHERE `login` = '$sender';");
    $sender_id = $sender_query['id'];
    $receiver = $_POST['contact'];
    $receiver_query = $file_db->query("SELECT id FROM collaborators WHERE `login`='$receiver'")->fetch();
    //$receiver_id = $file_db->exec(" SELECT id FROM collaborators WHERE `login` = '$receiver';");
    $receiver_id = $receiver_query['id'];
    $file_db->exec(" INSERT INTO messages (title, content, time_value, idExpediteur, idDestinataire) VALUES ('$title', '$message', '$time', '$sender_id', '$receiver_id');");
    
    echo "Insertion of message in DB done: ";
    echo "$title";
    echo "$message";
    echo "$time";
    echo "$sender_id";
    echo "$receiver_id";
}
    


    unset($pdo);
?>