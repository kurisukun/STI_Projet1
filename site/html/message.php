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


<?php

if (isset($_POST['Envoyer']) /*&& !empty($_POST['title']) && !empty($_POST['contact'])*/){
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $title = $_POST["title"];
    $message = $_POST["message"];
    $time = date('Y-m-d H:i:s');
    $sender = $_SESSION['username'];
    $sender_query = $file_db->query("SELECT id FROM collaborators WHERE `login`='$sender';")->fetch();
    $sender_id = $sender_query['id'];
    
    $receiver = $_POST['contact'];
    if(empty($receiver) || empty($title)){
        echo "  <div class='m-3 d-flex align-items-center justify-content-center'>
                    <div class='alert alert-danger'> The title and contact fields must be filled in </div>
                </div>";
    }
    else{

        $receiver_query = $file_db->query("SELECT id FROM collaborators WHERE `login`='$receiver'")->fetch();
        $receiver_id = $receiver_query['id'];
        if(empty($receiver_id)){
            echo "  <div class='m-3 d-flex align-items-center justify-content-center'>
                        <div class='alert alert-danger'> {$receiver} user does not exist! </div>
                    </div>";
        }           
        else{
            $file_db->exec(" INSERT INTO messages (title, content, time_value, idExpediteur, idDestinataire) VALUES ('$title', '$message', '$time', '$sender_id', '$receiver_id');");
        }
    }
}


    unset($pdo);
?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="<?php if(isset($_SESSION['retitle'])) echo $_SESSION['retitle']?>"/>
        </div>
        <div class="form-group col-md-6">
            <label for="contact">Contact</label>
            <input type="text" class="form-control" name="contact" id="contact" value="<?php if(isset($_SESSION['receiver'])) echo $_SESSION['receiver']?>"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="message">Message</label>
        <textarea class="form-control" name="message" id="message"></textarea>
    </div>
    <div>
        <input type="submit" class="form-control" type="Envoyer" name="Envoyer" />
    </div>


</form>



</div>


</body>
</html>