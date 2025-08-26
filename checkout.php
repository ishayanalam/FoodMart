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
    $totalAmount += $item['price'] * $item['quantity'];
}

?>

<div class="page-container" style="padding: 2rem;">
    <h1>Checkout</h1>
    <form action="process_order.php" method="POST">
        <h2>Order Summary</h2>
        <table class="cart-table" style="width: 100%; border-collapse: collapse;">
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
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['price'] * $item['quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Delivery Information</h3>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <h3>Order Total: BDT <?php echo number_format($totalAmount, 2); ?></h3>
        
        <button type="submit" class="btn btn-primary">Confirm Order</button>
    </form>
</div>

<?php
include 'components/footer.php';
?>
