<!DOCTYPE html>
<html>
<head>
    <title>Admin Products</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional CSS styles */
        body {
            padding: 20px;
        }
        .admin-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .admin-header {
            margin-bottom: 20px;
        }
        .admin-table {
            width: 100%;
        }
        .admin-table th {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1 class="admin-header">Admin Products</h1>

        <table class="admin-table table table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['description'] . '</td>';
                    echo '<td>' . $row['price'] . '</td>';
                    echo '<td><a href="edit_product.php?id=' . $row['id'] . '" class="btn btn-primary">Edit</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
