<?php
include 'db_connection.php';

$username = "admin1";
$password = "mypassword123";
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password) VALUES ('$username', '$hashedPassword')";
$connection->query($sql);

echo "Admin created successfully!";
?>
