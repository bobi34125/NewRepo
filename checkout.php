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
}
elseif ($row['name'] === 'Kyocera FS-1300d') {
                     $discountedPrices[$row['id']] = $row['price'] * 0.85; // Apply 15% discount}
}
      //   PRIMER ZA OTSTUPKA NA PRODUKT   elseif ($row['name'] === 'IME NA PRODUKTA') {
                   //  $discountedPrices[$row['id']] = $row['price'] //tova zavisi ot otstupkata koyato trqbva da bude prilojena v procenti i da se smetne *0.90,*0.95 i t.n * 0.90; // Apply 10% discount}//
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
    // Retrieve the customer information from the form
    $customerName = $_POST['customer_name'];
    $customerPhone = $_POST['customer_phone'];
    $customerEmail = $_POST['customer_email'];
    $customerCreditCard = $_POST['customer_credit_card'];
    $creditCardExpiry = $_POST['credit_card_expiry'];
    $creditCardCvv = $_POST['credit_card_cvv'];
    $customerAddress = $_POST['customer_address'];

    // Save the order in the database
    $productIds = implode(',', $productIds);
    $productQuantities = implode(',', array_column($cartProducts, 'quantity'));

    $query = "INSERT INTO orders (product_ids, product_quantities, total_price, customer_name, customer_phone, customer_email, customer_credit_card, credit_card_expiry, credit_card_cvv, customer_address)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssdsssssss', $productIds, $productQuantities, $totalPrice, $customerName, $customerPhone, $customerEmail, $customerCreditCard, $creditCardExpiry, $creditCardCvv, $customerAddress);
    mysqli_stmt_execute($stmt);

    // Clear the cart
    $_SESSION['cart'] = [];

    // Display success message
    echo '<center><h2>Your order has been placed successfully!</h2>';
    echo '<p>Thank you for your purchase.</p>';
    echo '<p>If you want you can go back and <a href="index.php">buy some other products</a></center>';
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        
        th {
            background-color: #f9f9f9;
        }
        
        img {
            width: 80px;
            height: 80px;
        }
        
        h2 {
            margin-top: 30px;
            margin-bottom: 10px;
            color: #333;
        }
        
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        input[type="submit"] {
            background-color: #FBAB7E;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #F7CE68;
        }
        
        .message {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .back-button {
            display: inline-block;
            background-color: #FBAB7E;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 20px;
        }
        
        .back-button:hover {
            background-color: #F7CE68;
        }
        
        .paypal-button {
            display: inline-block;
            background-color: #ffc439;
            color: #009cde;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
        }
           .original-price{
text-decoration: line-through;
}
        .visa-checkout-button {
            display: inline-block;
            background-color: #1a1f71;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>Checkout</h1>

    <?php if (!empty($cartProducts)): ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($cartProducts as $productId => $product): ?>
                <tr>
                    <td>
                        <img src="data:image/jpeg;base64,<?php echo $product['image']; ?>" alt="Product Image">
                        <?php echo $product['name']; ?>
                    </td>
                    <td>
                        <?php if (isset($discountedPrices[$productId])): ?>
                            <span class="original-price"><?php echo $product['formatted_price']; ?></span>
                            <span class="discounted-price"><?php echo number_format($discountedPrices[$productId], 2, '.', ''); ?></span>
                        <?php else: ?>
                            <?php echo $product['formatted_price']; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $product['quantity']; ?></td>
                    <td><?php echo number_format($product['subtotal'], 2, '.', ''); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="checkout-actions">
            <h2>Total Price: <?php echo number_format($totalPrice, 2, '.', ''); ?>лв.</h2>
            <br>
            <form method="post" action="checkout.php">
                <label for="customer_name">Name:</label>
                <input type="text" id="customer_name" name="customer_name" required>
                <label for="customer_phone">Phone:</label>
                <input type="text" id="customer_phone" name="customer_phone" required>
                <label for="customer_email">Email:</label>
                <input type="email" id="customer_email" name="customer_email" required>
                <label for="customer_credit_card">Credit Card Number:</label>
                <input type="text" id="customer_credit_card" name="customer_credit_card" required>
                <label for="credit_card_expiry">Credit Card Expiry:</label>
                <input type="text" id="credit_card_expiry" name="credit_card_expiry" required>
                <label for="credit_card_cvv">CVV:</label>
                <input type="text" id="credit_card_cvv" name="credit_card_cvv" required>
                <label for="customer_address">Address:</label>
                <input type="text" id="customer_address" name="customer_address" required>
                <br>
                <input type="submit" name="checkout" value="Checkout">
            </form>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>	
