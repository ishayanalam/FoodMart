<?php
session_start();
require_once 'db_connection.php';
include 'components/header.php';
include 'components/navbar.php';

// --- DYNAMIC CATEGORY ICONS ---
$categories_sql = "SELECT name, image_url FROM categories ORDER BY name ASC";
$categories_result = $connection->query($categories_sql);
?>

<div class="category-list">
    <?php if ($categories_result->num_rows > 0): ?>
        <?php while($category_row = $categories_result->fetch_assoc()): ?>
        <div class="each-category">
            <img src="assets/categories/<?php echo htmlspecialchars($category_row['image_url']); ?>" alt="<?php echo htmlspecialchars($category_row['name']); ?>"/>
            <p class="category-name"><?php echo htmlspecialchars(str_replace('_', ' ', $category_row['name'])); ?></p>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php
// --- DYNAMIC MENU ---

// Fetch ALL products (both available and unavailable)
$menu_sql = "
    SELECT 
        p.product_id AS id,
        p.name AS meal_name,
        p.description AS meal_description,
        p.price,
        p.image_url AS picture_url,
        p.is_available,
        c.name AS category
    FROM 
        products AS p
    JOIN 
        categories AS c ON p.category_id = c.category_id
    ORDER BY 
        c.name ASC, p.name ASC;
";

$menu_result = $connection->query($menu_sql);

// Group the results by category
$menuByCategory = [];
if ($menu_result->num_rows > 0) {
    while($row = $menu_result->fetch_assoc()) {
        $menuByCategory[$row['category']][] = $row;
    }
}

// Loop through the grouped array to display the menu
foreach ($menuByCategory as $category => $itemsInCategory):
?>
    <div class="menu-container">
        <h1 class="food-catergory--<?php echo htmlspecialchars($category); ?>"><?php echo str_replace('_', ' ', htmlspecialchars($category)); ?></h1>
        <div class="foods">

            <?php 
            foreach ($itemsInCategory as $item):
                // Check if the item is available
                if ($item['is_available']): 
            ?>
                    <div class="each-food">
                        <img src="./assets/Foods/<?php echo htmlspecialchars($item['picture_url']); ?>" alt="<?php echo htmlspecialchars($item['meal_name']); ?>"/>
                        <div class="food-info">
                            <h1 class="food-name"><?php echo htmlspecialchars($item['meal_name']); ?></h1>
                            <p class="food-details"><?php echo htmlspecialchars($item['meal_description']); ?></p>
                        </div>
                        <p class="food-price">Tk. <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="meal_id" value="<?php echo $item['id']; ?>">
                            <button type="submit">Add To Cart</button>
                        </form>
                    </div>
            <?php 
                else: 
            ?>
                    <div class="each-food unavailable">
                        <img src="./assets/Foods/<?php echo htmlspecialchars($item['picture_url']); ?>" alt="<?php echo htmlspecialchars($item['meal_name']); ?>"/>
                        <div class="food-info">
                            <h1 class="food-name"><?php echo htmlspecialchars($item['meal_name']); ?></h1>
                            <p class="food-details"><?php echo htmlspecialchars($item['meal_description']); ?></p>
                        </div>
                        <p class="food-price">Tk. <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
                        <button type="button" disabled>Sold Out</button>
                    </div>
            <?php 
                endif; // End the availability check
            endforeach; 
            ?>

        </div> 
    </div> 
<?php endforeach; ?>

<?php 
$connection->close(); // Close the database connection
include 'components/footer.php'; 
?>