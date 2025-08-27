<?php
session_start();
include 'db_connection.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin_accounts WHERE admin_username='$username' AND admin_password='$password'";
    $result = $connection->query($query);

    if ($result->num_rows === 1) {
        $_SESSION['admin_user'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p style='color:red;'>Invalid username or password</p>";
    }
}
?>