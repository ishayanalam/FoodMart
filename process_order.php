<?php
session_start();

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if cart is empty
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty. <a href='browse_food.php'>Continue shopping</a>.</p>";
        exit();
    }

    // Retrieve form data
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    
    // Calculate the total amount
    $totalAmount = 0;
    foreach ($_SESSION['cart'] as $mealId => $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }

    // Order details
    $orderDetails = [
        'items' => $_SESSION['cart'],
        'address' => $address,
        'phone' => $phone,
        'totalAmount' => $totalAmount,
        'orderDate' => date('Y-m-d H:i:s')
    ];

    // Ideally, save order to database or perform any other necessary steps.
    // For demonstration, we'll just output the order details.
    echo "<h2>Order Confirmed</h2>";
    echo "<p>Thank you for your order! Here's the summary:</p>";
    
    echo "<h3>Order Summary</h3>";
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr></thead><tbody>";
    foreach ($orderDetails['items'] as $mealId => $item) {
        echo "<tr><td>{$item['name']}</td><td>{$item['price']}</td><td>{$item['quantity']}</td><td>" . ($item['price'] * $item['quantity']) . "</td></tr>";
    }
    echo "</tbody></table>";
    
    // Displaying delivery details
    echo "<h3>Delivery Information</h3>";
    echo "<p>Address: $address</p>";
    echo "<p>Phone: $phone</p>";
    
    // Displaying the total amount
    echo "<h3>Total: BDT " . number_format($totalAmount, 2) . "</h3>";

    // Empty the cart after order confirmation
    unset($_SESSION['cart']);

    // Optionally, redirect to another page (e.g., payment or confirmation page)
    echo "<p>Your order has been processed successfully. You will receive an email confirmation shortly.</p>";
} else {
    echo "<p>Error: Invalid request.</p>";
}
?>
