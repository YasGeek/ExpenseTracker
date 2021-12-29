<?php
    $server_name = "localhost";
    $user_name = "root";
    $db_name = "expense";

try {
    $conn = new PDO("mysql:host=$server_name;dbname=$db_name", $user_name);
} catch (PDOException $e) {
    echo "<br>" . $e->getMessage();
}

session_start();
?>