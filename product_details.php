<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Custom styles for the product list page */

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        h2 {
            margin-top: 30px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            display: inline-block;
            margin-right: 10px;
        }

        a {
            text-decoration: none;
            color: #333;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 30px;
        }

        .product-item {
            width: 300px;
            margin: 20px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .product-item h3 {
            margin-top: 0;
        }

        .product-item p {
            margin-bottom: 10px;
        }

        .product-item img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .product-item button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .cart-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 30px;
            color: <?php echo ($isCartEmpty) ? 'black' : 'red'; ?>;
            text-decoration: none;
        }

        .cart-empty {
            color: black;
        }

        .cart-filled {
            color: red;
        }

        .search-form {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-input {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        .go-back-button {
            display: <?php echo (isset($_GET['search'])) ? 'flex' : 'none'; ?>;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .go-back-button a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            background-color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .go-back-button a:hover {
            background-color: #ddd;
        }

        .go-back-button a i {
            margin-right: 5px;
            font-size: 20px;
            color: red;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
                border-radius: 5px;
                margin-bottom: 10px;
            }

            .search-button {
                width: 100%;
                border-radius: 5px;
            }
        }

        .discount-label {
            color: #e74c3c;
            font-weight: bold;
        }

        .product-details {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
        }

        .product-details-image {
            width: 500px;
            margin-right: 30px;
        }

        .product-details-content {
            flex: 1;
        }

        .product-details h2 {
            margin-top: 0;
        }

        .product-details p {
            margin-bottom: 10px;
        }

        .product-details button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>
    <h1>Product List</h1>
    <h2>Categories</h2>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="index.php?category_id=<?php echo $category['id']; ?>">
                    <?php echo $category['name']; ?>
                </a>
                (<?php echo $category['total_products']; ?>)
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="search-form">
        <?php if (isset($_GET['search'])): ?>
            <form method="GET" action="index.php">
                <input type="text" class="search-input" name="search" placeholder="Search by product name" value="<?php echo $_GET['search']; ?>">
                <button type="submit" class="search-button">Search</button>
            </form>
        <?php else: ?>
            <form method="GET" action="index.php">
                <input type="text" class="search-input" name="search" placeholder="Search by product name">
                <button type="submit" class="search-button">Search</button>
            </form>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['search'])) { ?>
        <div class="go-back-button">
            <a href="#" onclick="goBack()"><i class="fas fa-arrow-left"></i>Go Back</a>
        </div>
    <?php } ?>

    <div class="product-list">
        <?php if (is_array($products) && count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <h3><?php echo $product['name']; ?></h3>
                    <?php if ($product['discount'] > 0): ?>
                        <p>
                            <span class="discount-label">Discounted Price:</span>
                            <span class="original-price"><?php echo $product['price']; ?></span>
                            <span class="discounted-price"><?php echo $product['price'] - $product['discount']; ?></span>
                        </p>
                    <?php else: ?>
                        <p>Price: <?php echo $product['price']; ?></p>
                    <?php endif; ?>
                    <a href="product_details.php?id=<?php echo $product['id']; ?>">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="Product Image" style="width: 100%; height: auto;">
                    </a>
                    <form method="POST" action="index.php"> <!-- Update the form action to the same page -->
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button> <!-- Update the button name to "add_to_cart" -->
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>

    <a href="cart.php" class="cart-icon">
        <i class="fas fa-shopping-cart <?php echo ($isCartEmpty) ? 'cart-empty' : 'cart-filled'; ?>" id="cart-icon"></i>
    </a>
    <script>
        var cartIcon = document.getElementById('cart-icon');
        if (cartIcon.classList.contains('cart-empty')) {
            cartIcon.style.color = 'black';
        } else if (cartIcon.classList.contains('cart-filled')) {
            cartIcon.style.color = 'red';
        }
    </script>
</body>
</html>