<?php

    require 'database.php';

    session_start();


    if( isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0){
        header("Location: account.php");
    }



    $emptyEm = ""; //empty Email
    $emptyPass = ""; //empty password
    $dupl = ""; //diplicate email
    $emailError = ""; //email not valid
    $errPass = ""; //password not valid
    $dataErr = ""; //database name check
    $emptyDate = ""; // required database
    $message = "";




    if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['database'])) {

        $email = $_POST["email"];

        $name = $_POST['database'];
        // check if database name  is valid or not

        $dataCheck = preg_match("/^[a-z A-Z0-9\\_\\\"]+$/", $_POST['database']);
        // check if e-mail address syntax is valid or not

        $check = preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $_POST['password']);
        if($dataCheck === 0 || $check === 0 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format";
            }
            if ($check === 0) {
                $errPass = "Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit";
            }
            if ($dataCheck === 0) {
                $dataErr = "Invalid database name";
            }
        }

        else {

            $sql = "CREATE DATABASE IF NOT EXISTS `$name`";
            $conn->exec($sql);
            $query = "CREATE TABLE IF NOT EXISTS $name.users
              (
                id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                email varchar(255),
                password varchar(250)
              )";
            $conn->exec($query);

            $st = $conn->prepare("SELECT email FROM $name.users WHERE ? = email");
            $params = array($_POST['email']);
            $st->execute($params);
            if ($st->rowCount() > 0) {

               $dupl = "Sorry, username already exists";
            }
            else {
                $password = md5($_POST['password']);
                $stmt = $conn->prepare("INSERT INTO $name.users (email, password) 
                VALUES (:email, :password)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);

                if($stmt->execute()){
                    $message =  "user created successfully";
                }
            }


        }

    }
    else {
        if (empty($_POST["database"])) {
            $emptyDate = "* Database is required";
        }
        if (empty($_POST["email"])) {
            $emptyEm = "* Email is required";
        }
        if (empty($_POST["password"])){
            $emptyPass = "* Password is required";
        }

    }


    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <title>Register Below</title>
            <link rel="stylesheet" type="text/css" href="style.css">
            <link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
        </head>
        <body>
            <div class="header">
                <a href="/">Flux</a>
            </div>

            <?php if(!empty($message)): ?>
                <p class="good"><?= $message ?></p>

            <?php endif; ?>

            <h1>Register</h1>
            <span>or <a href="login.php">login here</a></span>

            <form action="register.php" method="POST">

                <input type="text" placeholder="Enter the database name" name="database" >
                    <?php if(!empty($emptyDate)): ?>
                        <p class="bad"><?= $emptyDate ?></p>
                    <?php endif; ?>
                    <?php if(!empty($dataErr)): ?>
                        <p class="bad"><?= $dataErr ?></p>

                    <?php endif; ?>

                <input type="text" placeholder="Enter your email" name="email" >
                    <?php if(!empty($emptyEm)): ?>
                        <p class="bad"><?= $emptyEm ?></p>
                    <?php endif; ?>

                    <?php if(!empty($emailError)): ?>
                        <p class="bad"><?= $emailError ?></p>
                    <?php endif; ?>


                    <?php if(!empty($dupl)): ?>
                        <p class="bad"><?= $dupl ?></p>
                    <?php endif; ?>



                <input type="password" placeholder="and password" name="password" >
                    <?php if(!empty($emptyPass)): ?>
                        <p class="bad"><?= $emptyPass ?></p>
                    <?php endif; ?>


                    <?php if(!empty($errPass)): ?>
                        <p class="bad"><?= $errPass ?></p>

                    <?php endif; ?>
                <button type="submit">Register</button>

            </form>
        </body>
    </html>
