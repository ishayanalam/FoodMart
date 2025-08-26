<?php
include 'db_connection.php';

// Handle Mark as Delivered action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delivered_order_id'])) {
    $orderId = (int) $_POST['delivered_order_id'];
    $deliveryNote = $_POST['delivery_note'] ?? '';

    // Update order status to 'Delivered'
    $update = $connection->prepare("UPDATE orders SET order_status = 'Delivered' WHERE order_id = ? AND order_status = 'Ready for Delivery'");
    $update->bind_param("i", $orderId);
    $update->execute();
    $update->close();

    // Insert or update delivery note with delivery time
    $checkDelivery = $connection->prepare("SELECT delivery_id FROM delivery WHERE order_id = ?");
    $checkDelivery->bind_param("i", $orderId);
    $checkDelivery->execute();
    $checkDelivery->store_result();

    if ($checkDelivery->num_rows > 0) {
        // Update existing delivery record
        $updateDelivery = $connection->prepare("UPDATE delivery SET delivery_note = ?, dispatch_time = NOW() WHERE order_id = ?");
        $noteText = $deliveryNote . " | Delivered at " . date('Y-m-d H:i:s');
        $updateDelivery->bind_param("si", $noteText, $orderId);
        $updateDelivery->execute();
        $updateDelivery->close();
    } else {
        // Insert new delivery record
        $insertDelivery = $connection->prepare("INSERT INTO delivery (order_id, dispatch_time, delivery_note) VALUES (?, NOW(), ?)");
        $noteText = $deliveryNote . " | Delivered at " . date('Y-m-d H:i:s');
        $insertDelivery->bind_param("is", $orderId, $noteText);
        $insertDelivery->execute();
        $insertDelivery->close();
    }

    $checkDelivery->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<?php include 'components/admin/admin_header.php'; ?>

<style>
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 24px;
        padding: 24px;
    }

    .order-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .order-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px dashed #e5e7eb;
        padding-bottom: 12px;
    }

    .badge {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 999px;
        background: #fef9c3;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .customer, .order-meta {
        font-size: 15px;
        color: #334155;
        line-height: 1.6;
    }

    .items {
        background: #f1f5f9;
        border: 1px dashed #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        max-height: 220px;
        overflow: auto;
    }

    .items ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .items li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
    }

    .total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        padding-top: 8px;
        border-top: 1px solid #e5e7eb;
    }

    .actions {
        margin-top: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .btn {
        border: 1px solid #e5e7eb;
        background: #fff;
        padding: 10px 16px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 15px;
    }

    .btn-delivered {
        background: #16a34a;
        color: white;
        border-color: #16a34a;
    }

    textarea {
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        resize: vertical;
        min-height: 50px;
        font-size: 14px;
    }

    .empty {
        padding: 48px;
        text-align: center;
        color: #64748b;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        background: #f8fafc;
    }
</style>

<div class="container">
    <?php include 'components/admin/admin_navbar.php'; ?>

    <main class="main-content">
        <h2 style="margin:24px 24px 0 24px;">Orders Ready for Delivery</h2>

        <div class="orders-list">
            <?php
            $sql = "
                SELECT 
                    o.order_id, o.customer_id, o.order_date, o.total_amount, o.order_status,
                    c.name AS customer_name, c.phone, c.address
                FROM orders o
                JOIN customer c ON c.customer_id = o.customer_id
                WHERE o.order_status = 'Ready for Delivery'
                ORDER BY o.order_date ASC
            ";
            $result = $connection->query($sql);

            if ($result->num_rows === 0) {
                echo '<div class="empty">No orders are ready for delivery.</div>';
            } else {
                while ($o = $result->fetch_assoc()) {
                    $orderId = (int)$o['order_id'];

                    // fetch order items
                    $stmtItems = $connection->prepare("SELECT product_name, quantity, price FROM order_item WHERE order_id = ?");
                    $stmtItems->bind_param("i", $orderId);
                    $stmtItems->execute();
                    $itemsRes = $stmtItems->get_result();
                    $items = $itemsRes->fetch_all(MYSQLI_ASSOC);
                    $stmtItems->close();
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div style="font-weight:700;">Order #<?php echo $orderId; ?></div>
                                <div class="order-meta">
                                    Placed: <?php echo date('M d, Y h:i A', strtotime($o['order_date'])); ?>
                                </div>
                            </div>
                            <span class="badge">Ready for Delivery</span>
                        </div>

                        <div class="customer">
                            <div><strong>Customer:</strong> <?php echo htmlspecialchars($o['customer_name']); ?></div>
                            <div><strong>Phone:</strong> <?php echo htmlspecialchars($o['phone']); ?></div>
                            <div><strong>Address:</strong> <?php echo htmlspecialchars($o['address']); ?></div>
                        </div>

                        <div class="items">
                            <div style="font-weight:600; margin-bottom:6px;">Items</div>
                            <?php if (empty($items)): ?>
                                <div style="color:#6b7280;">No items found.</div>
                            <?php else: ?>
                                <ul>
                                    <?php foreach ($items as $it): ?>
                                        <li>
                                            <span><?php echo htmlspecialchars($it['product_name']); ?> × <?php echo (int)$it['quantity']; ?></span>
                                            <span>৳ <?php echo number_format((float)$it['price'], 2); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <div class="total">
                                <span>Total</span>
                                <span>৳ <?php echo number_format((float)$o['total_amount'], 2); ?></span>
                            </div>
                        </div>

                        <div class="actions">
                            <form method="post" style="margin:0; display:flex; flex-direction:column; gap:8px;">
                                <textarea name="delivery_note" placeholder="Leave a message for the customer..."></textarea>
                                <input type="hidden" name="delivered_order_id" value="<?php echo $orderId; ?>">
                                <button type="submit" class="btn btn-delivered">Mark as Delivered</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </main>
</div>

<?php include 'components/admin/admin_footer.php'; ?>
