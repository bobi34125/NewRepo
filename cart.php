<?php
session_start();
include 'config.php';

// Fetch the products added to the cart
$cartProducts = [];
$totalPrice = 0;
$discountedPrices = []; // Array to hold discounted prices

if (!empty($_SESSION['cart'])) {
    $productIds = array_filter($_SESSION['cart'], 'is_numeric'); // Filter out non-numeric values

    if (!empty($productIds)) {
        $placeholders = rtrim(str_repeat('?,', count($productIds)), ','); // Generate the placeholders for prepared statement
        $query = "SELECT * FROM products WHERE id IN ($placeholders)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, str_repeat('i', count($productIds)), ...$productIds);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $row['quantity'] = 0; // Initialize the quantity as 0
            $row['image'] = base64_encode($row['image']); // Convert the BLOB image data to base64
            $row['formatted_price'] = number_format($row['price'], 2, '.', ''); // Format the price with two decimal places
            $cartProducts[$row['id']] = $row;

            // Check if the product has a discount
            if ($row['name'] === 'Samsung Galaxy Y S5360') {
                $discountedPrices[$row['id']] = $row['price'] * 0.95; // Apply 5% discount
 } elseif ($row['name'] === 'LG G4') {
                     $discountedPrices[$row['id']] = $row['price'] * 0.95; // Apply 5% discount}
} elseif ($row['name'] === 'Acer Predator') {
                     $discountedPrices[$row['id']] = $row['price'] * 0.90; // Apply 10% discount}
}
elseif ($row['name'] === 'Kyocera FS-1300d') {
                     $discountedPrices[$row['id']] = $row['price'] * 0.85; // Apply 15% discount}
}
//    PRIMER ZA OTSTUPKA NA PRODUKT //  elseif ($row['name'] === 'IME NA PRODUKTA') {
                   //  $discountedPrices[$row['id']] = $row['price'] //tova zavisi ot otstupkata koyato trqbva da bude prilojena v procenti * 0.90; // Apply 10% discount}
          
        
            }
        

        // Calculate the quantity and total price
    foreach ($_SESSION['cart'] as $productId) {
    if (isset($cartProducts[$productId])) {
        $cartProducts[$productId]['quantity']++; // Increment the product's quantity
        $price = $cartProducts[$productId]['price']; // Get the product's price

        // Check if the product has a discount
        if (isset($discountedPrices[$productId])) {
            $price = $discountedPrices[$productId];
        }

        $cartProducts[$productId]['subtotal'] = $price * $cartProducts[$productId]['quantity']; // Calculate subtotal for the product
    }
}

// Calculate the total price based on product subtotals
$totalPrice = array_sum(array_column($cartProducts, 'subtotal'));

            }
        }
    


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['remove_id'])) {
        $removeProductId = $_POST['remove_id'];
        if (isset($cartProducts[$removeProductId])) {
            $_SESSION['cart'] = array_diff($_SESSION['cart'], array($removeProductId));
            unset($cartProducts[$removeProductId]);

            // Decrease the quantity of the removed product in the database
            $query = "UPDATE products SET quantity = quantity + 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $removeProductId);
            mysqli_stmt_execute($stmt);
        }
    } elseif (isset($_POST['checkout'])) {
        // Decrease the quantity of each product in the cart in the database
        foreach ($_SESSION['cart'] as $productId) {
            if (isset($cartProducts[$productId])) {
                $quantity = $cartProducts[$productId]['quantity'];

                if ($quantity > 0) {
                    $query = "UPDATE products SET quantity = quantity - 1 WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, 'i', $productId);
                    mysqli_stmt_execute($stmt);
                }
            }
        }} {
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reset array keys
        header('Location: checkout.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .cart-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .cart-item-image {
            flex: 0 0 100px;
            margin-right: 20px;
        }

        .cart-item-image img {
            max-width: 100%;
            height: auto;
        }

        .cart-item-details {
            flex-grow: 1;
        }

        .cart-item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
   .original-price{
text-decoration: line-through;
}
        .cart-item-price {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        .cart-item-quantity {
            color: #777;
            margin-bottom: 5px;
        }

        .cart-item-subtotal {
            font-weight: bold;
        }

        .cart-item-remove {
            flex: 0 0 100px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .cart-item-remove input[type="submit"] {
            width: 100%;
            background-color: #ff4c4c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cart-item-remove input[type="submit"]:hover {
            background-color: #d43f3f;
        }

        .cart-actions {
            margin-top: 20px;
            text-align: right;
        }

        .total-price-label {
            font-weight: bold;
        }

        .total-price-value {
            font-weight: bold;
            color: #555;
        }

        .checkout-button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .checkout-button:hover {
            background-color: #45a049;
        }

        .empty-cart-message {
            text-align: center;
            font-size: 18px;
            margin-top: 50px;
        }

        .back-to-products {
            position: absolute;
            left: 20px;
            font-size: 30px;
            color: #000;
            text-decoration: none;
            z-index: 9999;
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
        }

        .back-to-products:hover {
            transform: translateX(-5px);
            color: red;
        }

        .saobshtenie {
            font-size: 16px;
            color: #000;
            margin-top: 55px;
        }

        @media screen and (max-width: 767px) {
            .cart-item {
                flex-wrap: wrap;
            }

            .cart-item-image {
                flex: 0 0 100%;
                margin-bottom: 10px;
            }

            .cart-item-details {
                flex: 0 0 100%;
                margin-bottom: 10px;
            }

            .cart-item-remove {
                flex: 0 0 100%;
            }

            .cart-actions {
                text-align: center;
            }

            .total-price-label {
                font-weight: bold;
            }

            .total-price-value {
                font-weight: bold;
                color: #555;
            }

            .cart-item-price.discounted {
                text-decoration: line-through;
                color: #888;
            }

            .back-to-products {
                position: static;
                margin-top: 20px;
                font-size: 24px;
            }
        }
      </style>
</head>
<body>
    <h1>Cart</h1>

    <?php if (!empty($cartProducts)): ?>
        <div class="cart-container">
            <?php foreach ($cartProducts as $productId => $product): ?>
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="data:image/jpeg;base64,<?php echo $product['image']; ?>" alt="Product Image">
                    </div>
                    <div class="cart-item-details">
                        <div class="cart-item-name"><?php echo $product['name']; ?></div>
                        <div class="cart-item-price">
                            <?php if (isset($discountedPrices[$productId])): ?>
                                <span class="original-price"><?php echo $product['formatted_price']; ?></span>
                                <span class="discounted-price"><?php echo number_format($discountedPrices[$productId], 2, '.', ''); ?></span>
                            <?php else: ?>
                                <?php echo $product['formatted_price']; ?>
                            <?php endif; ?>
                        </div>
                        <div class="cart-item-quantity">Quantity: <?php echo $product['quantity']; ?></div>
                        <div class="cart-item-subtotal">Subtotal: <?php echo number_format($product['subtotal'], 2, '.', ''); ?></div>
                    </div>
                    <div class="cart-item-remove">
                        <form method="post" action="cart.php">
                            <input type="hidden" name="remove_id" value="<?php echo $productId; ?>">
                            <input type="submit" value="Remove">
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-actions">
                <!-- Display the total price and checkout button -->
                <span class="total-price-label">Total Price:</span>
                <span class="total-price-value"><?php echo number_format($totalPrice, 2, '.', ''); ?>лв.</span>
                <br>
                <form method="post" action="cart.php">
                    <input type="submit" name="checkout" value="Proceed to Checkout">
                </form>
            </div>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html> 