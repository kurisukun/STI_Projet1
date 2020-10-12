<?php
ob_start();
session_start();
include("header.html");
include("redirect.php");
?>

// TODO Bouton pour répondre
// TODO Supprimer le message

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

    $messages_query = $file_db->query(" SELECT * FROM messages WHERE `idExpediteur`=$user_id");
    

    $html = "";
    $i = 0;
    while($row = $messages_query->fetch(PDO::FETCH_ASSOC)){
        $i += 1;
        $receiver_query = $file_db->query(" SELECT login FROM collaborators WHERE `id` = '{$row['idDestinataire']}'; ")->fetch();
        $receiver = $receiver_query['login'];

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
                    <div class='text-muted'> <h6>From : {$receiver} </h6> </div>
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
                    <form action='<?php echo htmlspecialchars({$_SERVER['PHP_SELF']});?>' method='post'>
                        <input id='quoteid' name='{$row['id']}' value='{$row['id']}'/>
                        <input class='btn btn-dark' name='answer' value='Answer' type='button'/>    
                        <input class='btn btn-danger' name='delete' value='Delete' type='button'/>    
                    </form>
                </p>
            </div>
        </div>";
    }
    echo $html;


    /*
    if(isset($_POST['formDelete'])){
        if(isset($_POST['quoteid']) && !empty($_POST['quoteid'])){
            
            $quoteid = $_POST['quoteid'];
            echo "DELETE FROM quotes WHERE quoteid =".$quoteid;
            $result = $conn->query("DELETE FROM quotes WHERE quoteid =".$quoteid);
        }
    }*/
?>


    </body>
</html>