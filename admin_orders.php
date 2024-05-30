<?php
// admin_orders.php

session_start();
include 'config.php';
// Check if the user is logged in as admin
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit();
}

// Set the timezone for Bulgaria
date_default_timezone_set('Europe/Sofia');

// Retrieve orders from the database
$query = "SELECT * FROM orders ORDER BY order_date DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Handle order acceptance
if (isset($_POST['accept_order'])) {
    $orderId = $_POST['accept_order'];
    // Update the order status to accepted in the database
    $updateQuery = "UPDATE orders SET status = 'Accepted' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    // Redirect to the same page to refresh the order list
    header("Location: admin_orders.php");
    exit();
}

// Handle order deletion
if (isset($_POST['delete_order'])) {
    $orderId = $_POST['delete_order'];
    // Delete the order from the database
    $deleteQuery = "DELETE FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    // Redirect to the same page to refresh the order list
    header("Location: admin_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
    /* Add your modern CSS styles here */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    h1 {
        font-size: 24px;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .order-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .order-card {
        flex-basis: calc(33.33% - 20px);
        border: 1px solid #ddd;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        padding: 20px;
        transition: transform 0.3s ease-in-out; /* Add a subtle card transformation on hover */
    }

    .order-card:hover {
        transform: translateY(-5px); /* Move the card up slightly on hover */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Enhance shadow on hover */
    }

    .accepted-order {
        background-color: #e7f4e7;
    }

    .order-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .accept-btn, .delete-btn {
        padding: 6px 12px;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .accept-btn {
        background-color: #4CAF50;
    }

    .delete-btn {
        background-color: #f44336;
    }

    .accept-btn:hover, .delete-btn:hover {
        background-color: #333;
    }

    .accepted-status {
        color: darkred;
        font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
        .order-card {
            flex-basis: 100%;
        }
    }
</style>
</head>
<body>
    <div class="container">
        <h1>Admin Orders</h1>
        <div class="order-list">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="order-card <?php if ($row['status'] === 'Accepted') echo 'accepted-order'; ?>">
                    <div><strong>Order ID:</strong> <?php echo $row['id']; ?></div>
                    <div><strong>Customer Name:</strong> <?php echo $row['customer_name']; ?></div>
                    <div><strong>Customer Email:</strong> <?php echo $row['customer_email']; ?></div>
                    <div><strong>Customer Address:</strong> <?php echo $row['customer_address']; ?></div>
                    <div><strong>Customer Credit Card №:</strong> <?php echo $row['customer_credit_card']; ?></div>
                    <div><strong>Customer Credit Card CCV:</strong> <?php echo $row['credit_card_cvv']; ?></div>
                    <div><strong>Credit Card Expiry:</strong> <?php echo $row['credit_card_expiry']; ?></div>
                    <div><strong>Customer Phone:</strong> <?php echo $row['customer_phone']; ?></div>
                    <div><strong>Order Date:</strong> <?php echo date('d.m.Y H:i', strtotime($row['order_date'])); ?></div>
                    <div><strong>Product ID's:</strong> <?php echo $row['product_ids']; ?></div>
                    <div><strong>Product Names & Qty:</strong><br>
                        <?php
                        $productIds = explode(',', $row['product_ids']);
                        $quantities = explode(',', $row['product_quantities']);
                        $productPrices = explode(',', $row['product_prices']);

                        $productQuantities = array_count_values($productIds);

                        foreach ($productQuantities as $productId => $quantity) {
                            $productName = getProductById($conn, $productId);
                            $productIndex = array_search($productId, $productIds);
                            $price = isset($productPrices[$productIndex]) ? $productPrices[$productIndex] . ' лв' : 'Unknown Price';
                            echo $productName . ' (' . $quantity . ')' . "<br>";
                        }
                        ?>
                    </div>
                    <div><strong>Product Prices (лв):</strong><br>
                        <?php foreach ($productIds as $productId): ?>
                            <div class="product-price">
                                <?php
                                $originalPrice = getProductPriceById($conn, $productId);
                                $productName = getProductById($conn, $productId);
                                $discountedPrice = calculateDiscountedPrice($productName, $originalPrice);

                                if ($originalPrice != $discountedPrice) {
                                    echo '<span class="original-price">' . number_format($originalPrice, 2, '.', '') . ' лв</span>';
                                }
                                echo '<span class="discounted-price">' . number_format($discountedPrice, 2, '.', '') . ' лв</span>';
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div><strong>Total Price (лв):</strong> <?php echo $row['total_price'] . ' лв'; ?></div>
                    <div><strong>Status:</strong> <?php echo $row['status'] ?: 'Pending'; ?></div>
                    <div class="order-actions">
                        <!-- Accept Order button -->
                        <?php if ($row['status'] !== 'Accepted'): ?>
                            <form method="post" action="admin_orders.php">
                                <input type="hidden" name="accept_order" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="accept-btn">Accept Order</button>
                            </form>
                        <?php else: ?>
                            <span class="accepted-status">Order Accepted</span>
                        <?php endif; ?>
                        <!-- Delete Order button -->
                        <form method="post" action="admin_orders.php">
                            <input type="hidden" name="delete_order" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="delete-btn">Delete Order</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php
function getProductById($conn, $productId) {
    $productQuery = "SELECT name FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $productQuery);
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $productResult = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($productResult);
    return $product ? $product['name'] : 'Unknown Product';
}

function getProductPriceById($conn, $productId) {
    $productQuery = "SELECT price FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $productQuery);
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $productResult = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($productResult);
    return $product ? $product['price'] : 'Unknown Price';
}

function calculateDiscountedPrice($productName, $originalPrice) {
    // Define discounts for specific products
    $discounts = [
        'Samsung Galaxy Y S5360' => 0.95, // 5% discount for this product
        'LG G4' => 0.95 , // 5% discount for this product
         'Kyocera FS-1300d'=> 0.85 // 15% discount for this product
        // Add more discounts as needed
    ];

    if (isset($discounts[$productName])) {
        return $originalPrice * $discounts[$productName];
    }

    return $originalPrice;
}
?>