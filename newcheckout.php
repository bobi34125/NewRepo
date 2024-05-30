<?php
// checkout.php

session_start();
include 'config.php';

// Retrieve cart items from session
$cartItems = $_SESSION['cart'];

// Retrieve product IDs for each item
$productIDs = array_column($cartItems, 'product_id');

// Prepare the placeholders for the IN clause
$placeholders = rtrim(str_repeat('?,', count($productIDs)), ',');

// Prepare the query with placeholders
$query = "SELECT * FROM products WHERE id IN ($placeholders)";
$stmt = mysqli_prepare($conn, $query);

// Bind the product IDs as parameters
$paramTypes = str_repeat('i', count($productIDs));
mysqli_stmt_bind_param($stmt, $paramTypes, ...$productIDs);
mysqli_stmt_execute($stmt);

// Get the result set
$result = mysqli_stmt_get_result($stmt);

// Fetch the products
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <!-- Your CSS and other head elements -->
</head>
<body>
    <h1>Checkout</h1>
    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $cartItem): ?>
                <?php
                $product = array_filter($products, function ($product) use ($cartItem) {
                    return $product['id'] == $cartItem['product_id'];
                });
                $product = array_shift($product);
                ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><img src="<?php echo $product['image']; ?>" alt="Product Image" width="100"></td>
                    <td><?php echo $cartItem['quantity']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
