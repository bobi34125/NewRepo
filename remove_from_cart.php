<?php
session_start();

// Check if the product ID is submitted
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Remove the product from the cart
    if (($key = array_search($product_id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }
}

// Redirect back to the cart page
header('Location: cart.php');
exit();
?>