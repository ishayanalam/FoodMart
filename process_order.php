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

// customer table
$query = "INSERT INTO customer (name, phone, address) VALUES (?, ?, ?)";
$stmt = $connection->prepare($query);
$stmt->bind_param('sss', $customerName, $phone, $address);
$stmt->execute();
$customerId = $stmt->insert_id;  
$stmt->close();

//  orders table
$query = "INSERT INTO orders (customer_id, total_amount) VALUES (?, ?)";
$stmt = $connection->prepare($query);
$stmt->bind_param('id', $customerId, $totalAmount);
$stmt->execute();
$orderId = $stmt->insert_id;  
$stmt->close();

// order_item table
foreach ($_SESSION['cart'] as $mealId => $itemData) {
    $meal = $itemData['meal_name'] ?? '';
    $quantity = $itemData['quantity'] ?? 0;
    $price = $itemData['price'] ?? 0;
    
    $query = "INSERT INTO order_item (order_id, product_id, product_name, quantity, price) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('iisid', $orderId, $mealId, $meal, $quantity, $price);
    $stmt->execute();
    $stmt->close();
}

// cart clear
unset($_SESSION['cart']);

header('Location: order_details.php');
exit();
?>
