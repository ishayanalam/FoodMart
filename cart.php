<?php
session_start();
require_once 'db_connection.php';
include 'components/header.php';
include 'components/navbar.php';

$cartItems = $_SESSION['cart'] ?? [];
$menuItems = [];
$grandTotal = 0;

if (!empty($cartItems)) {
    $mealIds = array_keys($cartItems);
    $idsStr = implode(',', array_map('intval', $mealIds));

    $query = "
        SELECT product_id AS id, name AS meal_name, price, image_url AS picture_url, description
        FROM products
        WHERE product_id IN ($idsStr)
    ";

    $result = $connection->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $menuItems[$row['id']] = $row;
        }
    }
}
?>

<div class="page-container" style="padding: 2rem; max-width:800px; margin:auto;">
    <h2>Shopping cart</h2>
    <p>
        You have <strong><?php echo count($cartItems); ?></strong> 
        item<?php echo count($cartItems) > 1 ? 's' : ''; ?> in your cart
    </p>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="browse_food.php">Continue shopping</a>.</p>
    <?php else: ?>
        <!-- Update Cart Form -->
        <form action="add_to_cart.php" method="POST">
            <ul class="cart-list" style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:16px;">
                <?php foreach ($cartItems as $mealId => $itemData): 
                    $item = $menuItems[$mealId] ?? null;
                    if ($item):
                        $subtotal = $item['price'] * $itemData['quantity'];
                        $grandTotal += $subtotal;
                ?>
                <li class="cart-item" style="background:#fff; border-radius:12px; box-shadow:0 6px 14px rgba(0,0,0,.06); padding:16px; display:flex; justify-content:space-between; align-items:center;">
                    <div class="item-left" style="display:flex; gap:14px; align-items:center;">
                        <img src="./assets/Foods/<?php echo htmlspecialchars($item['picture_url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['meal_name']); ?>" 
                             style="width:70px; height:70px; object-fit:cover; border-radius:10px;">
                        <div>
                            <h3 style="margin:0; font-size:18px;"><?php echo htmlspecialchars($item['meal_name']); ?></h3>
                            <p style="margin:4px 0; font-size:14px; color:#6b7280;">
                                <?php echo htmlspecialchars($item['description'] ?? ''); ?>
                            </p>
                        </div>
                    </div>

                    <div class="item-right" style="display:flex; gap:20px; align-items:center;">
                        <input type="number" 
                               name="quantities[<?php echo $mealId; ?>]" 
                               value="<?php echo $itemData['quantity']; ?>" 
                               min="0" 
                               style="width:50px; text-align:center; padding:4px;">
                        <div class="price" style="min-width:70px; text-align:right; font-weight:600;">
                            Tk. <?php echo number_format($subtotal, 2); ?>
                        </div>
                        <!-- Delete item (via GET) -->
                        <a href="add_to_cart.php?remove=<?php echo $mealId; ?>" 
                           style="background:#f8f8f8; border:1px solid #ddd; border-radius:8px; padding:6px 10px; text-decoration:none; color:#000;">
                            🗑
                        </a>
                    </div>
                </li>
                <?php endif; endforeach; ?>
            </ul>

            <div style="margin-top:20px; text-align:right; font-size:18px; font-weight:bold;">
                Grand Total: Tk. <?php echo number_format($grandTotal, 2); ?>
            </div>

            <div style="margin-top:20px; text-align:right;">
                <button type="submit" name="quantities_submit" 
                        style="padding:10px 16px; border:none; background:#19b394; color:#fff; border-radius:10px; cursor:pointer;">
                    Update Cart
                </button>
                <a href="checkout.php" 
                   style="padding:10px 16px; background:#2563eb; color:#fff; border-radius:10px; text-decoration:none; margin-left:10px;">
                   Proceed to Checkout
                </a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'components/footer.php'; ?>
