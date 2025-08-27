<?php
session_start();
require_once 'data/menu_items.php';
include 'components/header.php';
include 'components/navbar.php';

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='browse_food.php'>Continue shopping</a>.</p>";
    exit();
}

// Calculate the total amount
$totalAmount = 0;
foreach ($_SESSION['cart'] as $mealId => $item) {
    if (isset($item['price']) && isset($item['quantity'])) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
}

?>

<!-- Linking the external CSS file -->
<link rel="stylesheet" type="text/css" href="checkout.css">

<div class="page-container">
    <div>
        <h1>Checkout</h1>
        <form action="process_order.php" method="POST">
            <h2>Order Summary</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $mealId => $item): ?>
                        <?php if (isset($item['name']) && isset($item['price']) && isset($item['quantity'])): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Order Total: BDT <?php echo number_format($totalAmount, 2); ?></h3>
    </div>

    <!-- Delivery Information Section -->
    <div class="delivery-info">
        <h3>Delivery Information</h3>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <div style="text-align: right;">
            <button type="submit" class="btn btn-primary">Confirm Order</button>
        </div>
    </div>

        </form>
</div>

<?php
include 'components/footer.php';
?>
