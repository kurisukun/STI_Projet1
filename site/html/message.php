<?php

use App\Auth;
use App\Database;
use App\Flash;

require 'includes.php';

Auth::check();
$pdo = Database::getInstance()->getPdo();


if (!empty($_POST)) {
    if (isset($_POST['title']) && isset($_POST['contact']) && isset($_POST['message'])) {
        // On récupère toutes les informations inscrites dans le formulaire
        $title = $_POST["title"];

        if(isset($_POST['old_message'])) {
            $message = htmlentities($_POST["message"]) . "\r\n\r\n" . htmlentities($_POST["old_message"]);
        } else {
            $message = htmlentities($_POST["message"]);
        }

        $time = date('Y-m-d H:i:s');
        $senderId = $_SESSION['user']['id'];
        $receiverId = $_POST['contact'];

        $req = $pdo->prepare("SELECT COUNT(*) as count FROM collaborators WHERE id=?");
        $req->execute([$receiverId]);
        $count = $req->fetch()['count'];

        if ($count === '1') {
            $req = $pdo->prepare("INSERT INTO messages (title, content, time_value, idExpediteur, idDestinataire) VALUES (?, ?, ?, ?, ?);");
            $result = $req->execute([
                $title,
                $message,
                $time,
                $senderId,
                $receiverId
            ]);
            if ($result) {
                Flash::success("Message sent successfully");
                header('Location: list_messages.php');
            } else {
                Flash::error("An error occured whiled sending the message");
                header('Location: message.php');
            }
            die();
        } else {
            Flash::error("Contact doesn't exist");
        }
        //On vérifie bien que le contact adressé existe bien dans la base de données
    } else {
        Flash::error('Some field are not filled successfully');
    }
}

$doAnswer = false;
$message = [];

$contacts = $pdo->query("SELECT * FROM collaborators")->fetchAll();

if (isset($_GET['answer_to'])) {
    $id = $_GET['answer_to'];

    // On vérifie si le message est bien pour l'utilisateur actuellement connecté
    $req = $pdo->prepare("SELECT * FROM messages INNER JOIN collaborators ON messages.idExpediteur = collaborators.id WHERE messages.id=:message_id AND idDestinataire=:user_id");
    $res = $req->execute(['message_id' => $id, 'user_id' => $_SESSION['user']['id']]);
    $message = $req->fetch();

    if ($message) {
        $doAnswer = true;
    } else {
        Flash::error("This message doesn't exist");
        header('Location: list_messages.php');
        die();
    }
}


include 'parts/header.php';
?>

    <div class="container">
        <h1><?= $doAnswer ? 'Respond to ' . $message['login'] : 'New message' ?></h1>
        <hr>
        <?php if ($doAnswer): ?>
            <div class="card mb-3">
                <div class="card-header">
                    Message
                </div>
                <div class="card-body">
                    <?= $message['content'] ?>
                </div>
            </div>
        <?php endif; ?>
        <form action="message.php" method="post">
            <div class="mb-3">
                <label class="form-label" for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title"
                       value="<?= $doAnswer ? 'Re: ' . $message['title'] : '' ?>"/>
            </div>
            <?php if ($doAnswer): ?>
                <input type="hidden" name="contact" value="<?= $message['idExpediteur'] ?>">
                <input type="hidden" name="old_message" value="<?= $message['content'] ?>">
            <?php else: ?>
                <div class="mb-3">
                    <label class="form-label" for="contact">Contact</label>
                    <select name="contact" id="contact" class="form-control">
                        <?php foreach ($contacts as $contact): ?>
                            <option value="<?= $contact['id'] ?>"><?= $contact['login'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label" for="message">Message</label>
                <textarea class="form-control" name="message" id="message" rows="10"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-lg"><?= $doAnswer ? 'Respond' : 'Send' ?></button>
            <a href="list_messages.php" class="btn btn-secondary btn-sm">Cancel</a>
        </form>
    </div>
<?php include 'parts/footer.php';