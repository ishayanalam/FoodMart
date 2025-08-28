<?php
session_start();
include 'db_connection.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // prevent SQL injection
    $stmt = $connection->prepare("SELECT password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['admin_user'] = $username;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Invalid username or password</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid username or password</p>";
    }

    $stmt->close();
}
?>
