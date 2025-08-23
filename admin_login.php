<?php
session_start();
include 'db_connection.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username='$username'";
    $result = $connection->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_user'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Invalid username or password</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid username or password</p>";
    }
}
?>
