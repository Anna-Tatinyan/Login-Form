<?php


    require 'database.php';
    session_start();

    if (isset($_SESSION['user_id'])) {
        header("Location: account.php");
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

        <?php if (!empty($user)):
            header("Location: account.php")
            ?>


        <?php else: ?>

            <h1>Please Login or Register</h1>
            <a href="login.php">Login</a> or
            <a href="register.php">Register</a>

        <?php endif; ?>

    </body>
</html>