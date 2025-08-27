<?php
session_start();
require_once 'db_connection.php';

// Ensure the cart is not empty
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty. <a href='browse_food.php'>Continue shopping</a>.";
    exit();
}

// Retrieve customer details 
$customerName = $_POST['customer_name'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$totalAmount = $_POST['total_amount'] ?? 0;

// Insert customer
$stmt = $connection->prepare("INSERT INTO customer (name, phone, address) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $customerName, $phone, $address);
$stmt->execute();
$customerId = $stmt->insert_id;
$stmt->close();

// Insert order
$stmt = $connection->prepare("INSERT INTO orders (customer_id, total_amount) VALUES (?, ?)");
$stmt->bind_param('id', $customerId, $totalAmount);
$stmt->execute();
$orderId = $stmt->insert_id;
$stmt->close();

// Insert order items
foreach ($_SESSION['cart'] as $mealId => $itemData) {
    $mealName = $itemData['meal_name']; // name from session
    $quantity = $itemData['quantity'];
    $price = $itemData['price'];

    $stmt = $connection->prepare("INSERT INTO order_item (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('iisid', $orderId, $mealId, $mealName, $quantity, $price);
    $stmt->execute();
    $stmt->close();
}

// Clear cart
unset($_SESSION['cart']);

// Redirect to confirmation page
header('Location: order_confirmation.php?order_id=' . $orderId);
exit();
?>
