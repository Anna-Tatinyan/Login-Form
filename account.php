<?php

    require 'database.php';
    session_start();

    if (isset($_SESSION['user_id'])) {
        $name = $_SESSION['db'];

        $records = $conn->prepare("SELECT id,email,password FROM $name.users WHERE id = :id");
        $records->bindParam(':id', $_SESSION['user_id']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        $user = NULL;

        if (count($results) > 0) {
            $user = $results;
        }

    }

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to your Web App</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
    </head>
    <body>

    <div class="header">
        <a href="/">Flux</a>
    </div>

    <?php
    if (!empty($user)): ?>
        <br/>Welcome <?= $user['email']; ?>
        <br/><br/>You are successfully logged in!
        <br/><br/>
        <a href="logout.php">Logout?</a>
    <?php else:
        header("Location: index.php");
        ?>
    <?php endif; ?>
    </body>
</html>


