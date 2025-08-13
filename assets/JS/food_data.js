const menuItems = [
  // --- Chefs_Specials ---
  {
    category: "Chefs_Special",
    picture_url: "steak.png",
    meal_name: "Premium Steak",
    meal_description:
      "A perfectly grilled, 10-ounce sirloin steak seasoned with our secret blend of herbs and spices. Served with roasted vegetables.",
    price: "Tk. 4000",
  },
  {
    category: "Chefs_Special",
    picture_url: "kacchi_biryani.png",
    meal_name: "Shahi Kacchi Biryani",
    meal_description:
      "Tender mutton and potatoes slow-cooked with aromatic basmati rice. A timeless classic from Old Dhaka.",
    price: "Tk. 750",
  },
  {
    category: "Chefs_Special",
    picture_url: "lobster_thermidor.jpg",
    meal_name: "Lobster Thermidor",
    meal_description:
      "A creamy mixture of cooked lobster meat, egg yolks, and brandy, stuffed into a lobster shell. An ultimate luxury.",
    price: "Tk. 5500",
  },
  {
    category: "Chefs_Special",
    picture_url: "mutton_rezala.png",
    meal_name: "Mutton Rezala",
    meal_description:
      "A traditional Bengali curry made with mutton, yogurt, and a paste of poppy seeds and cashew nuts. Rich and aromatic.",
    price: "Tk. 850",
  },
  {
    category: "Chefs_Special",
    picture_url: "beef_haleem.png",
    meal_name: "Shahi Beef Haleem",
    meal_description:
      "A rich and savory stew of beef, lentils, and wheat, slow-cooked for hours to perfection. Served with traditional garnishes.",
    price: "Tk. 650",
  },

  // --- Chicken ---
  {
    category: "Chicken",
    picture_url: "pasta_alfredo.png",
    meal_name: "Chicken Alfredo Pasta",
    meal_description:
      "Fettuccine pasta tossed in a rich, creamy parmesan sauce with grilled chicken breast and a hint of garlic.",
    price: "Tk. 680",
  },
  {
    category: "Chicken",
    picture_url: "caesar_salad.png",
    meal_name: "Grilled Chicken Caesar Salad",
    meal_description:
      "Crisp romaine lettuce, grilled chicken, croutons, and parmesan cheese tossed in a classic Caesar dressing.",
    price: "Tk. 600",
  },
  {
    category: "Chicken",
    picture_url: "tandoori_chicken.png",
    meal_name: "Tandoori Chicken (Half)",
    meal_description:
      "Chicken marinated in yogurt and spices, roasted in a tandoor oven until tender and juicy. A smoky delight.",
    price: "Tk. 550",
  },
  {
    category: "Chicken",
    picture_url: "chicken_sizzling.png",
    meal_name: "Chicken Sizzling",
    meal_description:
      "Tender pieces of chicken cooked with bell peppers and onions in a spicy sauce, served on a hot sizzling plate.",
    price: "Tk. 720",
  },
  {
    category: "Chicken",
    picture_url: "fried_chicken.png",
    meal_name: "Crispy Fried Chicken (8 pcs)",
    meal_description:
      "A bucket of our signature crispy and juicy fried chicken, perfect for sharing with friends and family.",
    price: "Tk. 1100",
  },

  // --- Burger ---
  {
    category: "Burger",
    picture_url: "chicken_burger.png",
    meal_name: "Classic Crispy Chicken Burger",
    meal_description:
      "A crispy fried chicken patty with fresh lettuce, tomatoes, and our signature sauce in a toasted brioche bun.",
    price: "Tk. 550",
  },
  {
    category: "Burger",
    picture_url: "beef_burger.png",
    meal_name: "Gourmet Beef Burger",
    meal_description:
      "A thick, juicy beef patty with melted cheddar cheese, caramelized onions, and pickles on a soft potato bun.",
    price: "Tk. 650",
  },
  {
    category: "Burger",
    picture_url: "naga_burger.png",
    meal_name: "Spicy Naga Burger",
    meal_description:
      "For the brave! A fiery beef or chicken patty infused with Naga chili sauce, topped with ghost pepper mayo.",
    price: "Tk. 680",
  },
  {
    category: "Burger",
    picture_url: "fish_burger.png",
    meal_name: "Crispy Fish Burger",
    meal_description:
      "A flaky, crumb-fried fish fillet with tangy tartar sauce and crisp lettuce, served in a soft bun.",
    price: "Tk. 580",
  },
  {
    category: "Burger",
    picture_url: "veggie_burger.png",
    meal_name: "Hearty Veggie Burger",
    meal_description:
      "A delicious patty made from mixed vegetables and chickpeas, topped with fresh avocado and yogurt sauce.",
    price: "Tk. 490",
  },

  // --- Seafood ---
  {
    category: "Seafood",
    picture_url: "grilled_prawns.png",
    meal_name: "Garlic Butter Prawns",
    meal_description:
      "Jumbo prawns sautéed in a fragrant garlic butter sauce, served with a lemon wedge and a side of crusty bread.",
    price: "Tk. 1200",
  },
  {
    category: "Seafood",
    picture_url: "fish_chips.png",
    meal_name: "Classic Fish and Chips",
    meal_description:
      "Battered and fried dory fish served with a generous portion of thick-cut fries and tartar sauce.",
    price: "Tk. 850",
  },
  {
    category: "Seafood",
    picture_url: "grilled_salmon.png",
    meal_name: "Grilled Salmon Steak",
    meal_description:
      "A healthy and delicious salmon steak, grilled to perfection and served with mashed potatoes and asparagus.",
    price: "Tk. 1800",
  },
  {
    category: "Seafood",
    picture_url: "crab_masala.png",
    meal_name: "Spicy Crab Masala",
    meal_description:
      "Fresh crab cooked in a rich and spicy onion-tomato gravy. A true coastal delicacy.",
    price: "Tk. 950",
  },

  // --- Bakery ---
  {
    category: "Bakery",
    picture_url: "brownie.png",
    meal_name: "Fudge Brownie with Ice Cream",
    meal_description:
      "A warm, gooey chocolate fudge brownie topped with a scoop of vanilla ice cream and drizzled with chocolate sauce.",
    price: "Tk. 450",
  },
  {
    category: "Bakery",
    picture_url: "red_velvet_cake.png",
    meal_name: "Red Velvet Cake Slice",
    meal_description:
      "A slice of moist red velvet cake with a rich cream cheese frosting. An elegant and decadent treat.",
    price: "Tk. 480",
  },
  {
    category: "Bakery",
    picture_url: "croissant.png",
    meal_name: "Butter Croissant",
    meal_description:
      "A flaky, buttery, and freshly baked croissant, perfect with a cup of coffee or as a light snack.",
    price: "Tk. 250",
  },
  {
    category: "Bakery",
    picture_url: "garlic_bread.png",
    meal_name: "Cheesy Garlic Bread",
    meal_description:
      "Toasted baguette slices topped with garlic butter, melted mozzarella cheese, and a sprinkle of herbs.",
    price: "Tk. 380",
  },

  // --- Beverage ---
  {
    category: "Beverage",
    picture_url: "mango_lassi.png",
    meal_name: "Refreshing Mango Lassi",
    meal_description:
      "A cool and creamy yogurt-based drink blended with sweet, ripe mangoes. Perfect for a hot day.",
    price: "Tk. 280",
  },
  {
    category: "Beverage",
    picture_url: "iced_tea.png",
    meal_name: "Iced Lemon Tea",
    meal_description:
      "A classic thirst-quencher. Freshly brewed tea chilled and served with a zesty slice of lemon.",
    price: "Tk. 220",
  },
  {
    category: "Beverage",
    picture_url: "chocolate_milkshake.png",
    meal_name: "Chocolate Milkshake",
    meal_description:
      "A thick and creamy milkshake made with premium chocolate ice cream and milk, topped with whipped cream.",
    price: "Tk. 350",
  },
  {
    category: "Beverage",
    picture_url: "lime_soda.png",
    meal_name: "Fresh Lime Soda",
    meal_description:
      "A bubbly and refreshing drink made with fresh lime juice and soda. Available sweet, salty, or mixed.",
    price: "Tk. 180",
  },
  {
    category: "Beverage",
    picture_url: "cappuccino.png",
    meal_name: "Hot Cappuccino",
    meal_description:
      "A perfect balance of rich espresso, steamed milk, and a smooth layer of foam. The ideal coffee break.",
    price: "Tk. 300",
  },
  {
    category: "Beverage",
    picture_url: "mineral_water.png",
    meal_name: "Mineral Water",
    meal_description:
      "A standard bottle of purified mineral water to keep you hydrated.",
    price: "Tk. 30",
  },
];
