<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user'])) {
    // Not logged in, redirect to login page
    header("Location: admin_login_form.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css" />
  </head>
<body>