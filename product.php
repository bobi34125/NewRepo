<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['product_id'])) {
    $productID = $_GET['product_id'];

    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $productID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!in_array($productID, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $productID;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">   
 <style>
        /* Custom styles for the product detail page */
       /* Reset default browser styles */
body, h1, ul, li {
    margin: 0;
    padding: 0;
    list-style: none;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
}

.product-container {
    max-width: 800px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.product-image img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.product-details {
    text-align: center;
    margin-top: 20px;
}

.product-price {
    font-size: 28px;
    font-weight: bold;
    color: #e74c3c;
    margin-bottom: 10px;
}

.add-to-cart-button {
    display: inline-block;
    padding: 15px 30px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 20px;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s;
}

.add-to-cart-button:hover {
    background-color: #45a049;
}
.cart-icon { 
position: fixed; 
top: 20px; 
right: 20px; 
font-size: 30px; 
color: <?php echo (empty($_SESSION['cart'])) ? 'black' : 'red'; ?>; 
text-decoration: none; 
}
.back-to-products {
    text-align: center;
    margin-top: 20px;
}

.back-to-products a {
    text-decoration: none;
    color: #fff;
    background-color: #3498db;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s;
}

.back-to-products a:hover {
    background-color: #2980b9;
}
    </style>
</head>
<body>
<a href="cart.php" class="cart-icon">
    <i class="fa fa-shopping-cart <?php echo ($isCartEmpty) ? 'cart-empty' : 'cart-filled'; ?>" id="cart-icon"></i>
</a>

    <h1><?php echo $product['name']; ?></h1>
    <div class="product-container">
        <div class="product-image">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="Product Image">
        </div>
        <div class="product-details">
            <?php if ($product['discount'] > 0): ?>
                <p>
                    <span class="product-price">$<?php echo $product['price']; ?></span>
                    <span class="discount-label">(<?php echo $product['discount']; ?>% off)</span>
                </p>
                <p>Discounted Price: $<?php echo $product['price'] - ($product['price'] * $product['discount'] / 100); ?></p>
            <?php else: ?>
                <p>Price: $<?php echo $product['price']; ?></p>
            <?php endif; ?>
            <form method="POST" action="product.php?product_id=<?php echo $product['id']; ?>">
                <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
            </form>
        </div>

        <div class="back-to-products">
            <a href="index.php">Back to Products</a>
        </div>
    </div>
</body>
</html>