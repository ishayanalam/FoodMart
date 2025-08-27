<?php
// 1. Start the PHP session to access the cart.
session_start();

// 2. Check if the form was submitted and an ID was sent.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['meal_id'])) {
    
    // 3. Get the meal ID from the form submission.
    $mealId = $_POST['meal_id'];

    // 4. If the cart doesn't exist in the session yet, create it as an empty array.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // 5. Add or update the item in the cart.
    if (isset($_SESSION['cart'][$mealId])) {
        // If the item is already in the cart, just increase its quantity.
        $_SESSION['cart'][$mealId]['quantity']++;
    } else {
        // If it's a new item, add it to the cart with a quantity of 1.
        $_SESSION['cart'][$mealId] = ['quantity' => 1];
    }
}

// 6. Redirect the user back to the menu page.
//    (Change 'browse_food.php' if your menu has a different name).
header("Location: browse_food.php");
exit(); // Always stop the script after a redirect.