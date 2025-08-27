<?php
session_start();

// --- REMOVE ITEM ---
// --- REMOVE ITEM ---
if (isset($_GET['remove'])) {
    $mealId = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$mealId])) {
        unset($_SESSION['cart'][$mealId]);
    }
    header("Location: cart.php");
    exit();
}


// --- UPDATE QUANTITIES ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $mealId => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) {
            unset($_SESSION['cart'][$mealId]); // remove if 0
        } else {
            $_SESSION['cart'][$mealId]['quantity'] = $qty;
        }
    }

    header("Location: cart.php");
    exit();
}

// --- ADD ITEM TO CART ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['meal_id'])) {
    $mealId = $_POST['meal_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$mealId])) {
        $_SESSION['cart'][$mealId]['quantity']++;
    } else {
        $_SESSION['cart'][$mealId] = ['quantity' => 1];
    }

    header("Location: browse_food.php");
    exit();
}
