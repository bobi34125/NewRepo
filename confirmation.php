<?php
session_start();
include 'config.php';

$username = $_SESSION['username'];

// Fetch order details
$query = "SELECT * FROM orders WHERE customer_name = '$username'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>

    <h2>Order Details:</h2>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="order-item">';
            echo '<h3>' . $row['product_name'] .'</h3>';
            echo '<p>$' . $row['product_price'] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No order details found.</p>';
    }
    ?>

    <p>Thank you for your order!</p>
</body>
</html>
