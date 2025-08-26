<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart'])) {
    
    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $mealId => $quantity) {
            // Sanitize input to ensure it's a non-negative integer
            $quantity = max(0, intval($quantity));

            if (isset($_SESSION['cart'][$mealId])) {
                if ($quantity == 0) {
                    // Remove item if quantity is set to 0
                    unset($_SESSION['cart'][$mealId]);
                } else {
                    // Otherwise, update the quantity
                    $_SESSION['cart'][$mealId]['quantity'] = $quantity;
                }
            }
        }
    }
}

// Redirect back to the cart page to see the changes
header("Location: cart.php");
exit();