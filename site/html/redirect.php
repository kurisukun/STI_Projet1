<?php
if(isset($_SESSION['userName'])) {
    echo "Your session is running " . $_SESSION['userName'];
}else{
    header('Location: login.php');
}
?>