<?php
session_start();
require_once 'data/menu_items.php'; // To get item details
include 'components/header.php';
include 'components/navbar.php';

// Helper function to find an item by its ID
function findItemById($items, $id) {
    foreach ($items as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    return null;
}
?>

<div class="checkout-container">
    <div class="cart-summary">
        <h1>Confirm Order</h1>

        <?php if (empty($_SESSION['cart'])): ?>
            <p>Your cart is empty. <a href="browse_food.php">Continue shopping</a>.</p>
        <?php else: ?>
            <form action="update_cart.php" method="POST">
                <div class="cart-item-list">
                    <?php
                    $grandTotal = 0;
                    foreach ($_SESSION['cart'] as $mealId => $itemData):
                        $itemDetails = findItemById($menuItems, $mealId);
                        if ($itemDetails):
                            $subtotal = $itemDetails['price'] * $itemData['quantity'];
                            $grandTotal += $subtotal;
                    ?>
                        <div class="cart-item">
                            <img src="<?php echo $itemDetails['image']; ?>" alt="<?php echo htmlspecialchars($itemDetails['meal_name']); ?>">
                            <div class="item-info">
                                <h2><?php echo htmlspecialchars($itemDetails['meal_name']); ?></h2>
                                <p><?php echo htmlspecialchars($itemData['description']); ?></p>
                                <div class="quantity">
                                    <input type="number" name="quantities[<?php echo $mealId; ?>]" value="<?php echo $itemData['quantity']; ?>" min="0" class="quantity-input">
                                </div>
                            </div>
                            <div class="item-price">
                                <span>$<?php echo number_format($itemDetails['price'], 2); ?></span>
                            </div>
                        </div>
                    <?php endif; endforeach; ?>
                </div>

                <div class="cart-total">
                    <p>Subtotal: $<?php echo number_format($grandTotal, 2); ?></p>
                    <p>Shipping: $4</p>
                    <p>Total (Tax incl.): $<?php echo number_format($grandTotal + 4, 2); ?></p>
                </div>

                <div class="checkout-buttons">
                    <button type="submit" name="update_cart" class="update-cart">Update Cart</button>
                    <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <div class="delivery-details">
        <h2>Delivery Details</h2>
        <form action="complete_order.php" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <button type="submit" class="complete-order">Complete Order</button>
        </form>
    </div>
</div>

<?php include 'components/footer.php'; ?>
