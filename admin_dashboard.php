<?php
include 'db_connection.php';
include 'components/admin/admin_header.php';


// -------------------- TODAY'S METRICS --------------------
$today = date('Y-m-d');

// Total orders today
$today_orders = $connection->query("SELECT COUNT(*) AS total FROM orders WHERE DATE(order_date)='$today'")->fetch_assoc()['total'];

// Total sale amount today
$today_sales = $connection->query("SELECT COALESCE(SUM(total_amount),0) AS total FROM orders WHERE DATE(order_date)='$today'")->fetch_assoc()['total'];

// Pending, ready, completed orders today
$today_pending = $connection->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='Pending' AND DATE(order_date)='$today'")->fetch_assoc()['total'];
$today_ready = $connection->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='Ready for Delivery' AND DATE(order_date)='$today'")->fetch_assoc()['total'];
$today_completed = $connection->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='Completed' AND DATE(order_date)='$today'")->fetch_assoc()['total'];

// Today's orders by category (ascending)
$today_category_orders = $connection->query("
    SELECT c.name AS category_name, COUNT(oi.order_item_id) AS total_items
    FROM order_item oi
    JOIN orders o ON oi.order_id = o.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN categories c ON p.category_id = c.category_id
    WHERE DATE(o.order_date)='$today'
    GROUP BY c.category_id
    ORDER BY c.name ASC
");

// -------------------- THIS MONTH'S METRICS --------------------
$this_month = date('Y-m');
$month_orders = $connection->query("SELECT COUNT(*) AS total FROM orders WHERE DATE_FORMAT(order_date,'%Y-%m')='$this_month'")->fetch_assoc()['total'];
$month_sales = $connection->query("SELECT COALESCE(SUM(total_amount),0) AS total FROM orders WHERE DATE_FORMAT(order_date,'%Y-%m')='$this_month'")->fetch_assoc()['total'];
$month_pending = $connection->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='Pending' AND DATE_FORMAT(order_date,'%Y-%m')='$this_month'")->fetch_assoc()['total'];
$month_ready = $connection->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='Ready for Delivery' AND DATE_FORMAT(order_date,'%Y-%m')='$this_month'")->fetch_assoc()['total'];
$month_completed = $connection->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='Completed' AND DATE_FORMAT(order_date,'%Y-%m')='$this_month'")->fetch_assoc()['total'];

// Category-wise items sold this month
$month_category_orders = $connection->query("
    SELECT c.name AS category_name, COUNT(oi.order_item_id) AS total_items
    FROM order_item oi
    JOIN orders o ON oi.order_id = o.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN categories c ON p.category_id = c.category_id
    WHERE DATE_FORMAT(o.order_date,'%Y-%m')='$this_month'
    GROUP BY c.category_id
    ORDER BY c.name ASC
");
?>

<style>
.dashboard-wrapper {
    display: flex;
    flex-direction: column;
    gap: 30px;
    padding: 30px;
}

.cards-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.card {
    background: #ffffff;
    flex: 1 1 250px;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}

.card h3 {
    margin: 0 0 10px 0;
    font-size: 1.2rem;
    color: #374151;
}

.card h2 {
    margin: 0 0 10px 0;
    font-size: 2rem;
    color: #111827;
}

/* Combined Status Summary */
.status-summary {
    margin-top: 15px;
    display: flex;
    gap: 20px;
    font-size: 1.4rem;
    font-weight: bold;
}

/* Prominent badges */
.status-badge {
    padding: 10px 18px;
    border-radius: 18px;
    font-size: 1.1rem;
    display: inline-block;
    color: #fff;
}

.status-pending { background: #b91c1c; }
.status-ready { background: #f59e0b; }
.status-completed { background: #16a34a; }

/* Separate month status cards */
.card-pending { background-color: #fef2f2; border-left: 5px solid #b91c1c; }
.card-ready { background-color: #fffbeb; border-left: 5px solid #f59e0b; }
.card-completed { background-color: #f0fdf4; border-left: 5px solid #16a34a; }
.card-pending h3 { color: #b91c1c; }
.card-ready h3 { color: #f59e0b; }
.card-completed h3 { color: #16a34a; }

.category-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.category-table th, .category-table td {
    border: 1px solid #e5e7eb;
    padding: 8px;
    text-align: center;
}

.category-table th {
    background-color: #f3f4f6;
    font-weight: bold;
    color: #374151;
}
</style>
<?php include 'components/admin/admin_navbar.php'; ?>

<div class="main-content dashboard-wrapper">

    <!-- Month Totals Row -->
    <div class="cards-row">
        <div class="card">
            <h3>Orders This Month</h3>
            <h2><?php echo $month_orders; ?></h2>
            <p>Total Sale: ৳ <?php echo number_format($month_sales,2); ?></p>
        </div>
        <div class="card card-pending">
            <h3>Pending Orders</h3>
            <h2><?php echo $month_pending; ?></h2>
        </div>
        <div class="card card-ready">
            <h3>Ready for Delivery</h3>
            <h2><?php echo $month_ready; ?></h2>
        </div>
        <div class="card card-completed">
            <h3>Completed Orders</h3>
            <h2><?php echo $month_completed; ?></h2>
        </div>
    </div>

    <!-- Today Section -->
    <h1>Today</h1>
    <div class="cards-row">
        <div class="card">
            <h3>Orders Today</h3>
            <h2><?php echo $today_orders; ?></h2>
            <p>Total Sale: ৳ <?php echo number_format($today_sales,2); ?></p>
            <div class="status-summary">
                <span class="status-badge status-pending">Pending: <?php echo $today_pending; ?></span>
                <span class="status-badge status-ready">Ready: <?php echo $today_ready; ?></span>
                <span class="status-badge status-completed">Completed: <?php echo $today_completed; ?></span>
            </div>

            <?php if($today_category_orders->num_rows > 0): ?>
            <table class="category-table">
                <thead>
                    <tr><th>Category</th><th>Items Ordered</th></tr>
                </thead>
                <tbody>
                    <?php while($row = $today_category_orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo $row['total_items']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Month Category Table -->
    <?php if($month_category_orders->num_rows > 0): ?>
    <div class="card">
        <h3>Items Sold by Category This Month</h3>
        <table class="category-table">
            <thead>
                <tr><th>Category</th><th>Items Sold</th></tr>
            </thead>
            <tbody>
                <?php while($row = $month_category_orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                    <td><?php echo $row['total_items']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</div>

<?php include 'components/admin/admin_footer.php'; ?>
