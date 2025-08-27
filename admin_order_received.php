<?php
include 'db_connection.php';

// Handle confirm or cancel actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_order_id'])) {
        $orderId = (int) $_POST['confirm_order_id'];

        // confirm order
        $update = $connection->prepare("UPDATE orders SET order_status = 'Confirmed' WHERE order_id = ? AND order_status = 'Pending'");
        $update->bind_param("i", $orderId);
        $update->execute();
        $update->close();

        // also insert into delivery table
        $stmtDelivery = $connection->prepare("INSERT INTO delivery (order_id, dispatch_time, delivery_note) VALUES (?, NOW(), 'Order confirmed and dispatched')");
        $stmtDelivery->bind_param("i", $orderId);
        $stmtDelivery->execute();
        $stmtDelivery->close();
    }

    if (isset($_POST['cancel_order_id'])) {
        $orderId = (int) $_POST['cancel_order_id'];

        // cancel order
        $update = $connection->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ? AND order_status = 'Pending'");
        $update->bind_param("i", $orderId);
        $update->execute();
        $update->close();
    }

    // redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<?php include 'components/admin/admin_header.php'; ?>

<style>
    .orders-wrap {
        display: flex;
        gap: 24px;
        padding: 24px;
    }
    .orders-list {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
    }
    .order-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .order-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px dashed #e5e7eb;
        padding-bottom: 8px;
    }
    .badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }
    .order-meta, .customer {
        font-size: 14px;
        color: #334155;
        line-height: 1.5;
    }
    .items {
        background: #f8fafc;
        border: 1px dashed #e2e8f0;
        border-radius: 12px;
        padding: 10px;
        max-height: 180px;
        overflow: auto;
    }
    .items ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 6px;
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
        padding-top: 6px;
        border-top: 1px solid #e5e7eb;
    }
    .actions {
        margin-top: 8px;
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    .button {
        border: 1px solid #e5e7eb;
        background: #fff;
        padding: 8px 12px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
    }
    .button-confirm {
        background: #16a34a;
        color: white;
        border-color: #16a34a;
    }
    .button-cancel {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
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
        <h2 style="margin:24px 24px 0 24px;">Pending Orders</h2>

        <div class="orders-wrap">
            <div class="orders-list">
                <?php
                $sql = "
                    SELECT 
                        o.order_id, o.customer_id, o.order_date, o.total_amount, o.order_status,
                        c.name AS customer_name, c.phone, c.address
                    FROM orders o
                    JOIN customer c ON c.customer_id = o.customer_id
                    WHERE o.order_status = 'Pending'
                    ORDER BY o.order_date ASC
                ";
                $result = $connection->query($sql);

                if ($result->num_rows === 0) {
                    echo '<div class="empty">No pending orders right now.</div>';
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
                                <span class="badge">Pending</span>
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
                                <form method="post" style="margin:0;">
                                    <input type="hidden" name="confirm_order_id" value="<?php echo $orderId; ?>">
                                    <button type="submit" class="button button-confirm">Confirm</button>
                                </form>
                                <form method="post" style="margin:0;">
                                    <input type="hidden" name="cancel_order_id" value="<?php echo $orderId; ?>">
                                    <button type="submit" class="button button-cancel">Cancel</button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </main>
</div>

<?php include 'components/admin/admin_footer.php'; ?>
