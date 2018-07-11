<?php
    $server = 'localhost';
    $username = 'root';
    $password = 'testmycode123';
    $dbname = 'information_schema';
        try{
            $conn = new PDO("mysql:host=$server", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e){
            echo "<br/>".$e->getMessage();
        }



?>

