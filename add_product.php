<?php
session_start();
include 'config.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle category creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
    $category_name = $_POST['category_name'];

    // Insert the new category into the categories table
    $sql = "INSERT INTO categories (name) VALUES ('$category_name')";
    if (mysqli_query($conn, $sql)) {
        $success = "Category added successfully.";
    } else {
        $error = "Error adding category: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_name'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $discount = $_POST['discount'] ?? 0;
    $discounted_price = $product_price - ($product_price * $discount / 100);
    $category_id = $_POST['category_id'];
    $product_quantity = $_POST['product_quantity']; // Get product quantity
    // Handle image upload
    $image = $_FILES['product_image'];
    $image_name = $image['name'];
    $image_tmp = $image['tmp_name'];
    $image_type = $image['type'];
}
    // Check if an image was selected
    if (!empty($image_name)) {
        // Read the image file
        $image_data = file_get_contents($image_tmp);

        // Escape special characters in the image data
        $image_data = mysqli_real_escape_string($conn, $image_data);

        // Insert the new product into the products table
       $sql = "INSERT INTO products (name, price, discount, discounted_price, image, category_id, quantity) VALUES ('$product_name', '$product_price', '$discount', '$discounted_price', '$image_data', '$category_id', '$product_quantity')";
    if (mysqli_query($conn, $sql)) {
        $success = "Product added successfully.";
    } else {
        $error = "Error adding product: " . mysqli_error($conn);
    }
}
// Retrieve the list of categories
$query = "SELECT * FROM categories";
$categoryResult = mysqli_query($conn, $query);
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        /* CSS styles for the add_product.php page */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .success-message {
            margin-top: 20px;
            padding: 10px;
            background-color: #dff0d8;
            color: #3c763d;
            border-radius: 5px;
        }

        .error-message {
            margin-top: 20px;
            padding: 10px;
            background-color: #f2dede;
            color: #a94442;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Product</h1>

        <!-- Category creation form -->
        <form method="POST" action="">
            <div class="form-group">
                <label>Category Name:</label>
                <input type="text" name="category_name" placeholder="Enter the category name" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Add Category">
            </div>
        </form>

        <?php if (isset($success)) { echo "<div class='success-message'>$success</div>"; } ?>
        <?php if (isset($error)) { echo "<div class='error-message'>$error</div>"; } ?>

        <!-- Product creation form -->
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="product_name" placeholder="Enter the product name" required>
            </div>
            <div class="form-group">
                <label>Product Price:</label>
                <input type="number" name="product_price" placeholder="Enter the product price" required>
            </div>
            <div class="form-group">
                <label>Discount (%):</label>
                <input type="number" name="discount" min="0" max="100" placeholder="Enter the discount percentage">
            </div>
            <div class="form-group">
                <label>Product Category:</label>
                <select name="category_id" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Product Quantity:</label>
                <input type="number" name="product_quantity" placeholder="Enter the product quantity" required>
            </div>
            <div class="form-group">
                <label>Product Image:</label>
                <input type="file" name="product_image" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Add Product">
            </div>
        </form>
    </div>
</body>
</html>