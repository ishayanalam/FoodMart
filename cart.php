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

<div class="page-container" style="padding: 2rem;">
    <h1>Your Shopping Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty. <a href="browse_food.php">Continue shopping</a>.</p>
    <?php else: ?>
        <form action="update_cart.php" method="POST">
            <table class="cart-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th style="width: 150px;">Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grandTotal = 0;
                    foreach ($_SESSION['cart'] as $mealId => $itemData):
                        $itemDetails = findItemById($menuItems, $mealId);
                        if ($itemDetails):
                            $subtotal = $itemDetails['price'] * $itemData['quantity'];
                            $grandTotal += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($itemDetails['meal_name']); ?></td>
                            <td>Tk. <?php echo htmlspecialchars($itemDetails['price']); ?></td>
                            <td>
                                <input type="number" name="quantities[<?php echo $mealId; ?>]" value="<?php echo $itemData['quantity']; ?>" min="0" style="width: 60px;">
                            </td>
                            <td>Tk. <?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                    <?php endif; endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align: right;">Grand Total</th>
                        <th>Tk. <?php echo number_format($grandTotal, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
            <div style="text-align: right; margin-top: 1rem;">
                <button type="submit" name="update_cart">Update Cart</button>
                 <a href="checkout.php" class="button-primary">Proceed to Checkout</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'components/footer.php'; ?>