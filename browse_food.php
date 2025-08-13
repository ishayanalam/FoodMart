<?php
session_start();
require_once 'data/menu_items.php';
include 'components/header.php';
include 'components/navbar.php';
?>

<div class="category-list">
  <div class="each-category">
    <img src="assets/categories/Baked.png" />
    <p class="category-name">Bakery</p>
  </div>
  <div class="each-category">
    <img src="assets/categories/Burger.png" />
    <p class="category-name">Burger</p>
  </div>
  <div class="each-category">
    <img src="assets/categories/Chicken.png" />
    <p class="category-name">Chicken</p>
  </div>
  <div class="each-category">
    <img src="assets/categories/Coffee.png" />
    <p class="category-name">Baverage</p>
  </div>
  <div class="each-category">
    <img src="assets/categories/Fish.png" />
    <p class="category-name">Seafood</p>
  </div>
</div>>


<?php
//dynamic menue starts

$categories = array_unique(array_column($menuItems, 'category'));

// Loop through each category
foreach ($categories as $category):
    // Get all items belonging to the current category
    $itemsInCategory = getItemsByCategory($menuItems, $category);
?>

    <div class="menu-container">
        <h1 class="food-catergory--<?php echo $category; ?>"><?php echo str_replace('_', ' ', $category); ?></h1>
        <div class="foods">

            <?php
            //generates html
            foreach ($itemsInCategory as $item):
            ?>
                <div class="each-food">
                    <img src="./assets/Foods/<?php echo htmlspecialchars($item['picture_url']); ?>" alt="<?php echo htmlspecialchars($item['meal_name']); ?>"/>
                    <div class="food-info">
                        <h1 class="food-name"><?php echo htmlspecialchars($item['meal_name']); ?></h1>
                        <p class="food-details">
                            <?php echo htmlspecialchars($item['meal_description']); ?>
                        </p>
                    </div>
                    <p class="food-price">Tk. <?php echo htmlspecialchars($item['price']); ?></p>
                    
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="meal_id" value="<?php echo $item['id']; ?>">
                        <button type="submit">Add To Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>

        </div> </div> <?php endforeach; ?>


<?php include 'components/footer.php'; ?>