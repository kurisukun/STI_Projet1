<?php

use App\Auth;
use App\Database;
use App\Flash;

require 'includes.php';

Auth::check();

$pdo = Database::getInstance()->getPdo();

// Vérifie que le bouton delet a bien été pressé
if (isset($_GET['delete'])) {
    // Et que l'id du message existe bien
    $id = $_GET['delete'];

    // On vérifie si le message a bien été envoyé par l'utilisateur actuellement connecté
    $messagesRequest = $pdo->prepare("SELECT COUNT(*) AS count FROM messages WHERE id=:message_id AND idDestinataire=:user_id");
    $messagesRequest->execute(['message_id' => $id, 'user_id' => $_SESSION['user']['id']]);
    $count = $messagesRequest->fetch()['count'];
    if($count === '1') {
        $messagesRequest = $pdo->prepare("DELETE FROM messages WHERE id=:id");
        $messagesRequest->execute(['id' => $id]);
    } else {
        Flash::error("This message doesn't exist");
    }
    header('Location: list_messages.php');
    die();
}

// Liste des messages adressés à l'utilisateur
$messagesRequest = $pdo->prepare("SELECT M.*, M.id AS message_id, C.*, C.id AS user_id FROM messages AS M INNER JOIN collaborators as C ON M.idExpediteur = C.id WHERE idDestinataire = ? ORDER BY time_value DESC");
$messagesRequest->execute([$_SESSION['user']['id']]);

include 'parts/header.php';
?>
    <div class="container">
        <a class="btn btn-secondary btn-lg mt-5" type="button" href="message.php">+ New message</a>

        <?php
        // Pour créer un id unique pour permettre à details de ne révéler qu'un seul contenu de message
        $i = 0;
        while ($row = $messagesRequest->fetch()):
            $i += 1;
            // Pour chaque message qui apparaît, on crée un bouton "Answer", "Delete" et surtout "Details"
            ?>
            <div class='card my-4 mx-auto w-75'>
                <div class='card-header'>
                    <div class='font-weight-bold'>
                        <h4 class='d-flex justify-content-between align-items-center'>
                            <?= $row['title'] ?>
                            <button class='btn btn-sm btn-dark' data-bs-toggle='collapse'
                                    data-bs-target='#collapse<?= $i ?>'
                                    type='button' aria-expanded='false' aria-controls='collapse<?= $i ?>'> Details
                            </button>
                        </h4>
                    </div>
                    <div class='text-muted'><h6>From : <?= $row['login'] ?></h6></div>
                    <div> <?= date('d.m.Y H:i', strtotime($row['time_value'])) ?></div>
                </div>
                <div class='collapse' id='collapse<?= $i ?>'>
                    <div class='card card-body text-justify' style="white-space: pre-line">
                        <?= $row['content'] ?>
                    </div>
                </div>

                <div class='card-footer text-center'>
                    <form action='' method='post'>
                        <a class='btn btn-dark' href="message.php?answer_to=<?= $row['message_id'] ?>">Answer</a>
                        <a class='btn btn-danger' href='list_messages.php?delete=<?= $row['message_id'] ?>'>Delete</a>
                    </form>
                </div>
            </div>
        <?
        endwhile;
        ?>

    </div>

<?php include 'parts/footer.php';