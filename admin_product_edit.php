<?php
include 'db_connection.php';

$message = '';
$delete_message = '';

// manage save/update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'save' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $is_available = isset($_POST['is_available']) ? 1 : 0;

        $stmt = $connection->prepare("UPDATE products SET price=?, is_available=? WHERE product_id=?");
        $stmt->bind_param("dii", $price, $is_available, $product_id);
        if ($stmt->execute()) {
            $message = "Product updated successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    if ($action === 'delete' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $stmt = $connection->prepare("DELETE FROM products WHERE product_id=?");
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
            $delete_message = "Product deleted successfully!";
        } else {
            $delete_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// fetch all products with category name
$sql = "SELECT p.product_id, p.name, p.price, p.is_available, c.name AS category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        ORDER BY p.product_id ASC";
$result = $connection->query($sql);
?>

<?php include 'components/admin/admin_header.php'; ?>

<style>
    .product-table {
        width: 90%;
        margin: 24px auto;
        border-collapse: collapse;
        font-size: 15px;
        color: #334155;
    }

    .product-table th, .product-table td {
        border: 1px solid #e5e7eb;
        padding: 12px 16px;
        text-align: left;
    }

    .product-table th {
        background-color: #f1f5f9;
        font-weight: 600;
    }

    .product-table tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .button-save {
        padding: 6px 12px;
        border-radius: 8px;
        border: none;
        background: #16a34a;
        color: white;
        cursor: pointer;
        margin-right: 8px;
    }

    .button-remove {
        padding: 6px 12px;
        border-radius: 8px;
        border: none;
        background: #b91c1c;
        color: white;
        cursor: pointer;
    }

    .message, .delete-message {
        padding: 12px;
        border-radius: 12px;
        font-weight: 500;
        text-align: center;
        width: 90%;
        margin: 16px auto;
    }

    .message { background: #d1fae5; color: #065f46; }
    .delete-message { background: #fee2e2; color: #991b1b; }

    input[type="number"] {
        width: 80px;
        padding: 6px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
    }

    input[type="checkbox"] {
        width: 20px;
        height: 20px;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0; right:0; bottom:0;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .modal-content {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        width: 400px;
        text-align: center;
    }

    .modal-content h3 { margin-bottom: 16px; }
    .modal-content p { margin-bottom: 24px; }

    .modal-content button {
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        font-size: 14px;
        cursor: pointer;
        margin: 0 8px;
    }

    .button-confirm { background: #b91c1c; color: white; }
    .button-cancel { background: #9ca3af; color: white; }
</style>

<div class="container">
    <?php include 'components/admin/admin_navbar.php'; ?>

    <main class="main-content">
        <h1 style="text-align:center; margin:40px 0 16px 0;">Edit Products</h1>

        <?php if($message) echo "<div class='message'>{$message}</div>"; ?>
        <?php if($delete_message) echo "<div class='delete-message'>{$delete_message}</div>"; ?>

        <table class="product-table">
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price (৳)</th>
                <th>Available</th>
                <th>Action</th>
            </tr>
            <?php
            $count = 1;
            while ($p = $result->fetch_assoc()): ?>
            <tr>
                <form method="post">
                    <td><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                    <td><?php echo htmlspecialchars($p['category_name']); ?></td>
                    <td>
                        <input type="number" name="price" value="<?php echo $p['price']; ?>" step="0.01" required>
                    </td>
                    <td>
                        <input type="checkbox" name="is_available" <?php echo ($p['is_available'] == 1) ? 'checked' : ''; ?>>
                    </td>
                    <td>
                        <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
                        <button type="submit" name="action" value="save" class="button-save">Save</button>
                        <button type="button" class="button-remove" onclick="confirmDelete('<?php echo htmlspecialchars($p['name']); ?>', <?php echo $p['product_id']; ?>)">Remove</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Modal -->
        <div class="modal" id="deleteModal">
            <div class="modal-content">
                <h3>Confirm Deletion</h3>
                <p id="modalText"></p>
                <form method="post" id="deleteForm">
                    <input type="hidden" name="product_id" id="deleteProductId">
                    <button type="submit" name="action" value="delete" class="button-confirm">Delete</button>
                    <button type="button" class="button-cancel" onclick="closeModal()">Cancel</button>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    function confirmDelete(name, id) {
        document.getElementById('modalText').innerText = `Are you sure you want to delete "${name}"?`;
        document.getElementById('deleteProductId').value = id;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>

<?php include 'components/admin/admin_footer.php'; ?>
