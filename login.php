<?php
    require 'database.php';

    session_start();

        $emptyEm = ""; //empty Email
        $emptyPass = ""; //empty password
        $emptyDate = ""; //empty database
        $message = ""; //do not match
        $errorData = "";

    if (isset($_SESSION['user_id'])) {
        header("Location: account.php");
    }
    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['database'])) {

        $name = $_POST['database'];

    try {
        $records = $conn->prepare("SELECT id,email,password FROM $name.users WHERE :email = email");
        $records->bindParam(':email', $email);
        $email = $_POST['email'];
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        if ($results && md5($_POST['password']) === $results['password']) {

            $_SESSION['user_id'] = $results['id'];
            $_SESSION['db'] = $name;
            header("Location: account.php");
        }
    }
        catch(PDOException $e){
            $e->getMessage();
            $errorData = "This database does not exist";
        }

    } else {
        if (empty($_POST["database"])) {
            $emptyDate = "* Database is required";
        }
        if (empty($_POST["email"])) {
            $emptyEm = "* Email is required";
        }
        if (empty($_POST["password"])) {
            $emptyPass = "* Password is required";
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login Below</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
    </head>
    <body>

    <div class="header">
        <a href="/">Flux</a>
    </div>

    <?php if (!empty($message)): ?>
        <p class="bad"><?= $message ?></p>
    <?php endif; ?>

    <h1>Login</h1>
    <span>or <a href="register.php">register here</a></span>

    <form action="login.php" method="POST">
        <?php if (!empty($errorData)): ?>
            <p class="bad"><?= $errorData ?></p>
        <?php endif; ?>
        <input type="text" placeholder="database" name="database">
            <?php if (!empty($emptyDate)): ?>
                <p class="bad"><?= $emptyDate ?></p>
            <?php endif; ?>
        <input type="text" placeholder="Enter your email" name="email">
            <?php if (!empty($emptyEm)): ?>
                <p class="bad"><?= $emptyEm ?></p>
            <?php endif; ?>
        <input type="password" placeholder="and password" name="password">
            <?php if (!empty($emptyPass)): ?>
                <p class="bad"><?= $emptyPass ?></p>
            <?php endif; ?>

        <button type="submit">Log in</button>

    </form>

    </body>
</html>