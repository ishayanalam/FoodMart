<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "foodmart";

$connection = new mysqli($host, $user, $pass, $db_name);

if ($connection->connect_error) {
    die("Failed to connect: " . $connection->connect_error);
}
?>
