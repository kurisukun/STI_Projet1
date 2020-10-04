<?php
if(isset($_SESSION['username'])) {
    echo "Your session is running " . $_SESSION['username'];
}else{
    header('Location: login.php');
}
?>