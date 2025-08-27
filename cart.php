<?php
session_start();
require_once 'data/menu_items.php'; // To get item details
include 'components/header.php';
include 'components/navbar.php';
require_once 'db_connection.php'; // Assuming you have a db_connection.php for your DB

// Helper function to find an item by its ID
function findItemById($items, $id) {
    foreach ($items as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    return null;
}

if (isset($_POST['update_cart'])) {
    //  update the cart in the database
    $order_id = $_SESSION['order_id']; // You can generate or retrieve the order_id from session or DB.

    foreach ($_POST['quantities'] as $mealId => $newQuantity) {
        $itemDetails = findItemById($menuItems, $mealId);
        if ($itemDetails) {
            // Prepare the SQL query to insert/update the order items
            $product_id = $itemDetails['id'];
            $product_name = $itemDetails['meal_name'];
            $price = $itemDetails['price'];
            $quantity = $newQuantity;

            // Check if the product already exists in the order 
            $checkQuery = "SELECT * FROM order_items WHERE order_id = ? AND product_id = ?";
            $stmt = $connection->prepare($checkQuery);
            $stmt->bind_param("ii", $order_id, $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Product already exists, update the quantity
                $updateQuery = "UPDATE order_items SET quantity = ?, price = ? WHERE order_id = ? AND product_id = ?";
                $stmt = $connection->prepare($updateQuery);
                $stmt->bind_param("idii", $quantity, $price, $order_id, $product_id);
                $stmt->execute();
            } else {
                // Insert new order item
                $insertQuery = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($insertQuery);
                $stmt->bind_param("iisis", $order_id, $product_id, $product_name, $quantity, $price);
                $stmt->execute();
            }
        }
    }

    // Redirect or show a success message
    header("Location: cart.php"); // Redirect back to the cart page
    exit();
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
                        <th style="text-align: left;">Product</th>
                        <th style="text-align: right;">Price</th>
                        <th style="text-align: center; width: 150px;">Quantity</th>
                        <th style="text-align: right;">Total</th>
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
                            <td style="text-align: right;">Tk. <?php echo htmlspecialchars($itemDetails['price']); ?></td>
                            <td style="text-align: center;">
                                <input type="number" name="quantities[<?php echo $mealId; ?>]" value="<?php echo $itemData['quantity']; ?>" min="0" style="width: 60px; text-align: center;">
                            </td>
                            <td style="text-align: right;">Tk. <?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                    <?php endif; endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align: right;">Grand Total</th>
                        <th style="text-align: right;">Tk. <?php echo number_format($grandTotal, 2); ?></th>
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
