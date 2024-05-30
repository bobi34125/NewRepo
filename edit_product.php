<?php
    session_start();
    include 'config.php';

    // Check if the admin is logged in
    if (!isset($_SESSION['admin_username'])) {
        header('Location: admin_login.php');
        exit();
    }

    // Check if the product ID is provided
    if (!isset($_GET['id'])) {
        header('Location: admin_products.php');
        exit();
    }

    // Get the product ID
    $product_id = $_GET['id'];

    // Fetch the product details
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $query);

    // Check if the product exists
    if (mysqli_num_rows($result) == 0) {
        header('Location: admin_products.php');
        exit();
    }

    $product = mysqli_fetch_assoc($result);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $description = $_POST['description'];

        // Update the product description
        $update_query = "UPDATE products SET description = '$description' WHERE id = '$product_id'";
        mysqli_query($conn, $update_query);

        // Redirect back to the product listing page
        header('Location: admin_products.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>

    <form method="POST" action="edit_product.php?id=<?php echo $product_id; ?>">
        <label>Product Name: <?php echo $product['name']; ?></label>
        <br>
        <label>Description:</label>
        <textarea name="description"><?php echo $product['description']; ?></textarea>
        <br>
        <label>Price: <?php echo $product['price']; ?></label>
        <br>
        <input type="submit" value="Save">
    </form>
</body>
</html>
