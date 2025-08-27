<?php
include 'db_connection.php';

$username = "admin";
$password = "admin";
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if admin already exists
$stmt = $connection->prepare("SELECT username FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Admin already exists!";
    $stmt->close();
    exit();
}
$stmt->close();

// Insert new admin
$stmt = $connection->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashedPassword);
if ($stmt->execute()) {
    echo "Admin created successfully!";
} else {
    echo "Error creating admin: " . $stmt->error;
}
$stmt->close();
?>
