<?php

use App\Auth;
use App\Database;

require 'includes.php';

Auth::check();

include 'parts/header.php';
?>

<div class="container">
    <h1>New message</h1>
    <hr>
    <form action="message.php" method="post">
        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title"
                   value="<?php if (isset($_SESSION['retitle'])) echo $_SESSION['retitle'] ?>"/>
        </div>
        <div class="mb-3">
            <label for="contact">Contact</label>
            <input type="text" class="form-control" name="contact" id="contact"
                   value="<?php if (isset($_SESSION['receiver'])) echo $_SESSION['receiver'] ?>"/>
        </div>
        <div class="mb-3">
            <label for="message">Message</label>
            <textarea class="form-control" name="message" id="message"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>


    <?php

    if (!empty($_POST['title']) && !empty($_POST['contact']) && !empty($_POST['message'])) {
        $pdo = Database::getInstance()->getPdo();

        // On récupère toutes les informations inscrites dans le formulaire
        $title = $_POST["title"];
        $message = $_POST["message"];
        $time = date('Y-m-d H:i:s');
        $sender = $_SESSION['username'];
        $sender_query = $pdo->query("SELECT id FROM collaborators WHERE `login`='$sender';")->fetch();
        $sender_id = $sender_query['id'];
        $receiver = $_POST['contact'];

        // Si le contact ou le titre du message n'est pas renseigné, on affiche une erreur
        if (empty($receiver) || empty($title)) {
            echo "  <div class='m-3 d-flex align-items-center justify-content-center'>
                    <div class='alert alert-danger'> The title and contact fields must be filled in </div>
                </div>";
        } else {
            $receiver_query = $pdo->query("SELECT id FROM collaborators WHERE `login`='$receiver'")->fetch();
            $receiver_id = $receiver_query['id'];

            //On vérifie bien que le contact adressé existe bien dans la base de données
            if (empty($receiver_id)) {
                echo "  <div class='m-3 d-flex align-items-center justify-content-center'>
                        <div class='alert alert-danger'> {$receiver} user does not exist! </div>
                    </div>";
            } else {
                // Envoi du message
                $pdo->exec(" INSERT INTO messages (title, content, time_value, idExpediteur, idDestinataire) VALUES ('$title', '$message', '$time', '$sender_id', '$receiver_id');");
            }
        }
    }
    ?>
</div>
<?php include 'parts/footer.php';