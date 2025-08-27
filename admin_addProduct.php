<?php
include 'db_connection.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image_url = $_POST['image_url'] ?? '';
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $category_id = $_POST['category_id'] ?? NULL;

    if ($name && $price && $category_id) {
        $stmt = $connection->prepare("INSERT INTO products (name, description, price, image_url, is_available, category_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiii", $name, $description, $price, $image_url, $is_available, $category_id);
        if ($stmt->execute()) {
            $message = "Product added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Please fill in all required fields (Name, Price, Category).";
    }
}

$categories = $connection->query("SELECT category_id, name FROM categories ORDER BY name ASC");
?>

<?php include 'components/admin/admin_header.php'; ?>

<style>
    .page-heading {
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        margin: 40px 0 16px 0;
        color: #111827;
    }

    .form-container {
        width: 500px;
        margin: 0 auto 40px auto;
        padding: 24px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-container input,
    .form-container textarea,
    .form-container select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-container label {
        font-weight: 600;
        margin-bottom: 4px;
    }

    .button-submit {
        padding: 12px 16px;
        border-radius: 12px;
        border: none;
        background: #2563eb;
        color: white;
        font-size: 15px;
        cursor: pointer;
        margin-top: 8px;
    }

    .message {
        padding: 12px;
        border-radius: 12px;
        background: #fef3c7;
        color: #92400e;
        font-weight: 500;
        text-align: center;
    }
</style>

<div class="container">
    <?php include 'components/admin/admin_navbar.php'; ?>

    <main class="main-content">
        <h1 class="page-heading">Add New Product</h1>

        <div class="form-container">
            <?php if ($message) echo "<div class='message'>{$message}</div>"; ?>
            <form method="post">
                <label for="name">Product Name *</label>
                <input type="text" name="name" id="name" required>

                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4"></textarea>

                <label for="price">Price (৳) *</label>
                <input type="number" name="price" id="price" step="0.01" required>

                <label for="image_url">Image URL</label>
                <input type="text" name="image_url" id="image_url">

                <label for="category_id">Category *</label>
                <select name="category_id" id="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endwhile; ?>
                </select>

                <label>
                    <input type="checkbox" name="is_available" checked> Available
                </label>

                <button type="submit" class="button-submit">Add Product</button>
            </form>
        </div>
    </main>
</div>

<?php include 'components/admin/admin_footer.php'; ?>
