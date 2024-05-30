<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Your CSS styles here */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #fff; /* White background */
            color: #333; /* Dark gray text color */
        }

        h1 {
            text-align: center;
            color: #333; /* Dark gray text color */
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: #444; /* Dark gray background */
            overflow: hidden;
        }

        nav li {
            float: left;
        }

        nav li a {
            display: block;
            padding: 20px;
            text-decoration: none;
            color: #fff; /* White text color */
        }
footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            color: #000;
        }
        nav li a:hover {
            background-color: #555; /* Slightly lighter gray on hover */
        }

        nav li a i {
            color: #fff; /* White icon color */
            margin-right: 10px;
        }

        h2 {
            margin-top: 30px;
            margin-bottom: 10px;
            color: #555; /* Dark gray text color */
        }

       
        /* Media query for screens smaller than 768px */
        @media (max-width: 768px) {
            nav ul {
                text-align: center;
            }
    footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            color: #000;
        }
            nav li {
                float: none;
                display: inline-block;
            }

            nav li a {
                padding: 10px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <h1>Welcome to the Admin Dashboard</h1>

    <nav>
        <ul>
            <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> Add Product</a></li>
            <li><a href="delete_user.php"><i class="fas fa-user-times"></i> Delete User</a></li>
            <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> View Orders</a></li>
            <!-- Add more navigation links as needed -->
        </ul>
    </nav>

    <h2>Dashboard Content</h2>
    <p>Welcome to the Admin Dashboard. This dashboard provides you with the following features:</p>

    <ul>
        <li><strong>Add Product:</strong> Use this feature to add new products to your e-commerce store.</li>
        <li><strong>Delete User:</strong> Delete user accounts and manage your user base.</li>
        <li><strong>View Orders:</strong> Access and review customer orders and order history.</li>
    </ul>

    
         <footer>
        <p>Admin &copy; 2023</p>
    </footer>
</body>
</html>

