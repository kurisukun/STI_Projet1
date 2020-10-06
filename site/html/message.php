<?php include("header.html")?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Message</title>
</head>
<body>

<form action="insert.php" method="post">
    <p>
        <label for="title">Title</label>
        <input type="text" name="title" id="title"/>
        <label for="message">Message</label>
        <input type="text" name="message" id="message"/>
    </p>

    <p>
        <input type="submit" value="Envoyer" />
    </p>
 </form>

 </body>
</html>
