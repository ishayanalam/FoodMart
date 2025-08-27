<?php
session_start();
require_once 'data/menu_items.php';
include 'components/header.php';
include 'components/navbar.php';
include 'db_connection.php'; //  db conn

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='browse_food.php'>Continue shopping</a>.</p>";
    exit();
}

// Calculate the total amount
$totalAmount = 0;
foreach ($_SESSION['cart'] as $mealId => $item) {
    // Check if required keys exist before using them
    if (isset($item['price'], $item['quantity'])) {
        $totalAmount += $item['price'] * $item['quantity'];
    } else {
        // Handle the case where required keys are missing
        echo "<p>Error: Missing item details for meal ID: $mealId</p>";
    }
}

// Capture the delivery details (if available)
$name = isset($_POST['name']) ? $_POST['name'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';

// Insert the order into the database
if (!empty($name) && !empty($address) && !empty($phone)) {
    // SQL query to insert the order
    $sql = "INSERT INTO orders (customer_name, customer_address, customer_phone, total_amount, order_date) 
            VALUES ('$name', '$address', '$phone', '$totalAmount', NOW())";

    if (mysqli_query($conn, $sql)) {
        $orderId = mysqli_insert_id($conn); // Get the ID of the newly created order
        
        // Insert order items into the order_items table
        foreach ($_SESSION['cart'] as $mealId => $item) {
            if (isset($item['price'], $item['quantity'])) {
                $sqlItem = "INSERT INTO order_items (order_id, meal_id, quantity, price) 
                            VALUES ('$orderId', '$mealId', '{$item['quantity']}', '{$item['price']}')";
                mysqli_query($conn, $sqlItem);
            }
        }

        echo "<p>Order placed successfully! Your order ID is: $orderId</p>";
    } else {
        echo "<p>Error placing order: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Confirm Order</title>
  <link rel="stylesheet" href="checkout.css" />
</head>
<body>
  <main class="page">
    <section class="cart-pane">
      <h1 class="page-title">Confirm Order</h1>
      <hr class="divider" />
      <div class="cart-header">
        <h2>Shopping cart</h2>
        <p>You have <strong><?php echo count($_SESSION['cart']); ?></strong> item(s) in your cart</p>
      </div>

      <!-- Cart list -->
      <ul class="cart-list">
        <?php foreach ($_SESSION['cart'] as $mealId => $item): ?>
        <li class="cart-item">
          <div class="item-left">
            <?php if (isset($item['image'], $item['name'], $item['description'])): ?>
                <img class="item-img" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="item-info">
                    <h3 class="item-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="item-sub"><?php echo htmlspecialchars($item['description']); ?></p>
                </div>
            <?php else: ?>
                <p>Item details are missing for meal ID: <?php echo $mealId; ?></p>
            <?php endif; ?>
          </div>
          <div class="item-right">
            <div class="qty">
              <span class="qty-value"><?php echo isset($item['quantity']) ? $item['quantity'] : 0; ?></span>
            </div>
            <div class="price"><?php echo isset($item['price']) ? 'Tk' . number_format($item['price'], 2) : 'Tk 0.00'; ?></div>
            <button class="trash" aria-label="Remove item">🗑</button>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <!-- Delivery details panel -->
    <aside class="details-pane">
      <h2 class="details-title">Delivery Details</h2>

      <form class="details-form" action="#" method="post">
        <label class="field">
          <span class="label">Name</span>
          <input type="text" name="name" placeholder="Enter your name" required />
        </label>

        <label class="field">
          <span class="label">Address</span>
          <textarea name="address" rows="4" placeholder="Enter your address" required></textarea>
        </label>

        <label class="field">
          <span class="label">Phone</span>
          <input type="tel" name="phone" placeholder="Enter your phone number" required />
        </label>
      </form>

      <div class="summary">
        <div class="row">
          <span>Subtotal</span>
          <span class="amount"><?php echo '$' . number_format($totalAmount, 2); ?></span>
        </div>
        <div class="row">
          <span>Shipping</span>
          <span class="amount">$0.00</span>
        </div>
        <div class="row total">
          <span>Total (Tax incl.)</span>
          <span class="amount"><?php echo '$' . number_format($totalAmount, 2); ?></span>
        </div>
      </div>

      <button class="btn-primary" type="submit">
        <span>Complete Order</span>
        <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>
    </aside>
  </main>
</body>
</html>

<?php
include 'components/footer.php';
?>
