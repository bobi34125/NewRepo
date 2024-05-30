<?php
session_start();
include 'config.php';

// Get the product ID and quantity from the form
$productID = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Retrieve the product from the database
$query = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $productID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// Calculate the discounted price
$discountedPrice = $product['price'] * (1 - $product['discount']);

// Add the product to the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the product is already in the cart
if (isset($_SESSION['cart'][$productID])) {
    // Update the quantity
    $_SESSION['cart'][$productID] += $quantity;
} else {
    // Add the product to the cart with the quantity
    $_SESSION['cart'][$productID] = $quantity;
}

// Redirect to the index page
header('Location: index.php');
exit();
?>
