<?php
session_start();
require_once 'db_connection.php';

// Get the order ID from the URL
$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    echo "Order not found. <a href='browse_food.php'>Go back</a>.";
    exit();
}

// Fetch order details
$stmt = $connection->prepare("
    SELECT o.order_id, o.total_amount, o.order_status, o.order_date,
           c.name AS customer_name, c.phone, c.address
    FROM orders o
    JOIN customer c ON o.customer_id = c.customer_id
    WHERE o.order_id = ?
");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$orderDetails = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$orderDetails) {
    echo "Order not found. <a href='browse_food.php'>Go back</a>.";
    exit();
}

// Fetch order items
$stmt = $connection->prepare("
    SELECT product_name, quantity, price
    FROM order_item
    WHERE order_id = ?
");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$orderItems = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmation</title>
<style>
    body { font-family: Arial, sans-serif; background: #f9fafb; color: #111; padding: 20px; }
    .order-container { max-width: 800px; margin: 40px auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 6px 16px rgba(0,0,0,0.08); }
    h2 { text-align: center; margin-bottom: 24px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    th { background: #f3f4f6; }
    .total { font-weight: bold; text-align: right; }
    .status { display:inline-block; padding:6px 12px; border-radius:12px; color:#fff; font-weight:600; font-size:14px; }
    .status-pending { background:#f59e0b; }
    .status-completed { background:#16a34a; }
    .status-processing { background:#2563eb; }
    .back-link { display:block; margin-top:20px; text-align:center; }
</style>
</head>
<body>

<div class="order-container">
    <h2>Order Confirmation</h2>

    <p><strong>Order ID:</strong> <?php echo $orderDetails['order_id']; ?></p>
    <p><strong>Customer:</strong> <?php echo htmlspecialchars($orderDetails['customer_name']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($orderDetails['phone']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($orderDetails['address']); ?></p>
    <p><strong>Order Date:</strong> <?php echo $orderDetails['order_date']; ?></p>
    <p>
        <strong>Status:</strong>
        <span class="status <?php
            echo $orderDetails['order_status']=='Pending'?'status-pending':
                 ($orderDetails['order_status']=='Completed'?'status-completed':'status-processing');
        ?>">
            <?php echo $orderDetails['order_status']; ?>
        </span>
    </p>

    <h3>Ordered Items:</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price (৳)</th>
            </tr>
        </thead>
        <tbody>
            <?php while($item = $orderItems->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'],2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p class="total">Total Amount: ৳ <?php echo number_format($orderDetails['total_amount'],2); ?></p>

    <a class="back-link" href="browse_food.php">Back To Home!</a>
</div>

</body>
</html>
