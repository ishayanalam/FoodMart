<?php
include 'db_connection.php';

$message = '';
$order = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? '';

    if ($order_id) {
        $stmt = $connection->prepare("
            SELECT o.order_id, c.name AS customer_name, o.order_status, o.total_amount, o.order_date,
                   GROUP_CONCAT(CONCAT(oi.product_name, ' (x', oi.quantity, ')') SEPARATOR ', ') AS items
            FROM orders o
            JOIN customer c ON o.customer_id = c.customer_id
            JOIN order_item oi ON o.order_id = oi.order_id
            WHERE o.order_id = ?
            GROUP BY o.order_id
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        if (!$order) $message = "No order found with ID $order_id.";
        $stmt->close();
    } else {
        $message = "Please enter an Order ID.";
    }
}
?>

<?php include 'components/header.php'; ?>
<?php include 'components/navbar.php'; ?>

<style>
.track-container {
    max-width: 700px;
    margin: 40px auto;
    padding: 32px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 12px 28px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.track-container form {
    display: flex;
    gap: 12px;
}
.track-container input {
    flex: 1;
    padding: 12px 14px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 15px;
}
.track-container button {
    padding: 12px 20px;
    border-radius: 10px;
    border: none;
    background: #2563eb;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}
.track-container button:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}
.message {
    color: #b91c1c;
    font-weight: 500;
}
.order-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
    border: 1px solid #e5e7eb;
    padding: 20px;
    border-radius: 12px;
    background: #f9fafb;
}
.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}
.status-pending { background: #b91c1c; }
.status-ready { background: #f59e0b; }
.status-completed { background: #16a34a; }
.progress-bar {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}
.progress-step {
    flex: 1;
    height: 12px;
    border-radius: 8px;
    background: #e5e7eb;
}
.progress-step.active { background: #2563eb; }
</style>

<div class="track-container">
    <h1 style="text-align:center;">Track Your Order</h1>

    <form method="post">
        <input type="number" name="order_id" placeholder="Enter Order ID" required>
        <button type="submit">Track</button>
    </form>

    <?php if($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if($order): ?>
        <div class="order-info">
            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Total:</strong> ৳ <?php echo number_format($order['total_amount'],2); ?></p>
            <p><strong>Items:</strong> <?php echo htmlspecialchars($order['items']); ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
            <p>
                <strong>Status:</strong> 
                <span class="status-badge 
                    <?php 
                        echo $order['order_status']=='Pending'?'status-pending':
                             ($order['order_status']=='Ready for Delivery'?'status-ready':'status-completed'); 
                    ?>">
                    <?php echo $order['order_status']; ?>
                </span>
            </p>

          
        </div>
    <?php endif; ?>
</div>

<?php include 'components/footer.php'; ?>
