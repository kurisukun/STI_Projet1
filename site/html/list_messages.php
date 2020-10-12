<?php
ob_start();
session_start();
include("header.php");
include("redirect.php");
?>

// TODO Bouton pour répondre

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Message</title>
    </head>
    <body>


<?php
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user = $_SESSION['username'];
    $user_query = $file_db->query("SELECT id FROM collaborators WHERE `login`='$user';")->fetch();
    $user_id = $user_query['id'];

    $messages_query = $file_db->query(" SELECT * FROM 'messages' WHERE idDestinataire = {$user_id} ORDER BY time_value DESC;");

    $html = "";
    $i = 0;
    while($row = $messages_query->fetch(PDO::FETCH_ASSOC)){
        $i += 1;
        $sender_query = $file_db->query(" SELECT login FROM collaborators WHERE `id` = '{$row['idExpediteur']}'; ")->fetch();
        $sender = $sender_query['login'];

        $html .= 
        "<div class='card m-5 w-75'>
            <div class='card-header'>
                <p>    
                    <div class='font-weight-bold'> 
                        <h4 class='d-flex justify-content-between align-items-center'> 
                        {$row['title']}
                        <button class='btn btn-sm btn-dark' data-toggle='collapse' data-target='#collapse{$i}' type='button' aria-expanded='false' aria-controls='collapse{$i}'> Details </button>
                        </h4> 
                    </div>
                    <div class='text-muted'> <h6>From : {$sender} </h6> </div>
                    <div> {$row['time_value']} </div>
                </p>
            </div>
            <div class='collapse' id='collapse{$i}'>
                <div class='card card-body text-justify'>
                    {$row['content']}
                </div>
            </div>
        
            <div class='card-footer text-center'>
                <p>
                    <form action='' method='post'>
                        <input id='messageid' name='messageid' value='{$row['id']}'/>
                        <input class='btn btn-dark' name='answer' value='Answer' type='submit'/>    
                        <input class='btn btn-danger' name='delete' value='Delete' type='submit'/>    
                    </form>
                </p>
            </div>
        </div>";
    }
    echo $html;
?>

<?php
    if(isset($_POST['delete'])){
        if(isset($_POST['messageid'])){
            $message_id = $_POST['messageid'];
            $file_db->exec(" DELETE FROM messages WHERE id=$message_id; ");
            header('Location: '.$_SERVER['REQUEST_URI']);
            die();
        }
    }
?>

    </body>
</html>