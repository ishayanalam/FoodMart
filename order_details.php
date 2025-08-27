<?php
session_start();
require_once 'db_connection.php';


if (!isset($_SESSION['order_id'])) {
    header('Location: process_order.php');
    exit();
}

$orderId = $_SESSION['order_id'];

// Query the order details
$query = "
    SELECT o.order_id, o.total_amount, c.name AS customer_name, c.phone, c.address
    FROM orders o
    JOIN customer c ON o.customer_id = c.customer_id
    WHERE o.order_id = ?
";
$stmt = $connection->prepare($query);
$stmt->bind_param('i', $orderId);
$stmt->execute();
$result = $stmt->get_result();
$orderDetails = $result->fetch_assoc();
$stmt->close();

// Query the items in the order
$query = "SELECT oi.product_name, oi.quantity, oi.price
          FROM order_item oi
          WHERE oi.order_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('i', $orderId);
$stmt->execute();
$orderItems = $stmt->get_result();
$stmt->close();

// Clear the session order ID
unset($_SESSION['order_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="order-confirmation">
        <h2>Thank You for Your Order!</h2>
        <p>Your order has been placed successfully.</p>

        <h3>Order Details:</h3>
        <p><strong>Order ID:</strong> <?php echo $orderDetails['order_id']; ?></p>
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($orderDetails['customer_name']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($orderDetails['phone']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($orderDetails['address']); ?></p>
        <p><strong>Total Amount:</strong> BDT <?php echo number_format($orderDetails['total_amount'], 2); ?></p>

        <h3>Ordered Items:</h3>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $orderItems->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>BDT <?php echo number_format($item['price'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <p><a href="browse_food.php">Thanks For Your Order! Continue Shopping</a></p>
    </div>

</body>
</html>
