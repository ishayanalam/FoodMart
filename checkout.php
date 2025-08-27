<?php
session_start();
require_once 'db_connection.php';
include 'components/header.php';
include 'components/navbar.php';

// --- fetch cart from session ---
$cartItems = $_SESSION['cart'] ?? [];
if (empty($cartItems)) {
    echo "<p>Your cart is empty. <a href='browse_food.php'>Continue shopping</a>.</p>";
    exit();
}

$menuItems = [];
$totalAmount = 0;

// Fetch product details from DB
$mealIds = array_keys($cartItems);
$idsStr = implode(',', array_map('intval', $mealIds));

$query = "
    SELECT product_id AS id, name AS meal_name, price
    FROM products
    WHERE product_id IN ($idsStr)
";
$result = $connection->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menuItems[$row['id']] = $row;
    }
}

// Update session cart with meal names and price
foreach ($cartItems as $mealId => $itemData) {
    if (isset($menuItems[$mealId])) {
        $_SESSION['cart'][$mealId]['meal_name'] = $menuItems[$mealId]['meal_name'];
        $_SESSION['cart'][$mealId]['price'] = $menuItems[$mealId]['price'];
    }
}

?>

<div class="page-container" style="padding: 2rem; max-width:1100px; margin:auto; display:flex; gap:40px;">

    <!-- Left: Order Summary -->
    <div style="flex:2;">
        <h2>Order Summary</h2>
        <table style="width:100%; border-collapse: collapse; background:#fff; border-radius:8px; overflow:hidden;">
            <thead style="background:#f3f4f6;">
                <tr>
                    <th style="padding:10px; text-align:left;">Product</th>
                    <th style="padding:10px; text-align:right;">Price</th>
                    <th style="padding:10px; text-align:center;">Quantity</th>
                    <th style="padding:10px; text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $mealId => $itemData): 
                    $subtotal = $itemData['price'] * $itemData['quantity'];
                    $totalAmount += $subtotal;
                ?>
                    <tr>
                        <td style="padding:10px;"><?php echo htmlspecialchars($itemData['meal_name']); ?></td>
                        <td style="padding:10px; text-align:right;">Tk. <?php echo number_format($itemData['price'], 2); ?></td>
                        <td style="padding:10px; text-align:center;"><?php echo $itemData['quantity']; ?></td>
                        <td style="padding:10px; text-align:right;">Tk. <?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot style="background:#f9fafb; font-weight:bold;">
                <tr>
                    <td colspan="3" style="padding:10px; text-align:right;">Grand Total</td>
                    <td style="padding:10px; text-align:right;">Tk. <?php echo number_format($totalAmount, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Right: Delivery Information -->
    <div style="flex:1; background:#fff; padding:20px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <h2>Delivery Information</h2>
        <form action="process_order.php" method="POST" style="display:flex; flex-direction:column; gap:12px;">
            <label>
                <span>Name:</span><br>
                <input type="text" name="customer_name" required style="width:100%; padding:8px;">
            </label>

            <label>
                <span>Address:</span><br>
                <textarea name="address" rows="3" required style="width:100%; padding:8px;"></textarea>
            </label>

            <label>
                <span>Phone:</span><br>
                <input type="text" name="phone" required style="width:100%; padding:8px;">
            </label>

            <!-- Pass total hidden -->
            <input type="hidden" name="total_amount" value="<?php echo $totalAmount; ?>">

            <button type="submit" 
                    style="padding:10px; background:#2563eb; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold;">
                Confirm Order
            </button>
        </form>
    </div>
</div>

<?php include 'components/footer.php'; ?>
