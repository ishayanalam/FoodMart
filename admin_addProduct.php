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
/* -------------------- MODERN ADD PRODUCT CSS -------------------- */
.page-heading {
    text-align: center;
    font-size: 32px;
    font-weight: 700;
    margin: 40px 0 24px 0;
    color: #111827;
}

.form-container {
    max-width: 600px;
    margin: 0 auto 40px auto;
    padding: 32px 36px;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    gap: 22px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.form-container:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
}

.form-container label {
    font-weight: 600;
    margin-bottom: 6px;
    color: #2c3e50; /* Primary accent color */
    display: flex;
    align-items: center;
    gap: 6px;
}

.input-icon-wrapper {
    position: relative;
    width: 100%;
}

.input-icon-wrapper i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

.input-icon-wrapper input,
.input-icon-wrapper textarea,
.input-icon-wrapper select {
    padding-left: 36px;
}

.form-container input,
.form-container textarea,
.form-container select {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 15px;
    color: #111827;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-container input:focus,
.form-container textarea:focus,
.form-container select:focus {
    outline: none;
    border-color: #2c3e50;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
    accent-color: #2c3e50;
}

.button-submit {
    padding: 14px 18px;
    border-radius: 12px;
    border: none;
    background: #2c3e50;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

.button-submit:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

.message {
    padding: 14px 16px;
    border-radius: 12px;
    background: #fef3c7;
    color: #92400e;
    font-weight: 500;
    text-align: center;
    border: 1px solid #fcd34d;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}
</style>

<div class="container">
    <?php include 'components/admin/admin_navbar.php'; ?>

    <main class="main-content">
        <h1 class="page-heading">Add New Product</h1>

        <div class="form-container">
            <?php if ($message) echo "<div class='message'>{$message}</div>"; ?>

            <form method="post">
                <div class="input-icon-wrapper">
                    <label for="name"><i class="fas fa-utensils"></i> Product Name *</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="input-icon-wrapper">
                    <label for="description"><i class="fas fa-align-left"></i> Description</label>
                    <textarea name="description" id="description" rows="4"></textarea>
                </div>

                <div class="input-icon-wrapper">
                    <label for="price"><i class="fas fa-money-bill-wave"></i> Price (৳) *</label>
                    <input type="number" name="price" id="price" step="0.01" required>
                </div>

                <div class="input-icon-wrapper">
                    <label for="image_url"><i class="fas fa-image"></i> Image URL</label>
                    <input type="text" name="image_url" id="image_url">
                </div>

                <div class="input-icon-wrapper">
                    <label for="category_id"><i class="fas fa-list"></i> Category *</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <label>
                    <input type="checkbox" name="is_available" checked> Available
                </label>

                <button type="submit" class="button-submit">Add Product</button>
            </form>
        </div>
    </main>
</div>

<?php include 'components/admin/admin_footer.php'; ?>
