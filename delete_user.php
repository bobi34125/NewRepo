<?php
session_start();
include 'config.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle delete user action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Delete the user from the users table
    $sql = "DELETE FROM users WHERE id = '$user_id'";
    if (mysqli_query($conn, $sql)) {
        $success = "User deleted successfully.";
    } else {
        $error = "Error deleting user: " . mysqli_error($conn);
    }
}

// Handle change password action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    // Update the user's password in the users table
    $sql = "UPDATE users SET password = '$new_password' WHERE id = '$user_id'";
    if (mysqli_query($conn, $sql)) {
        $success = "Password changed successfully.";
    } else {
        $error = "Error changing password: " . mysqli_error($conn);
    }
}

// Retrieve all users from the users table
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .user-table {
            width: 100%;
            overflow-y: auto;
            max-height: 400px;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
border: 1px solid;
        }

        th, td {
           border-collapse: collapse;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .change-password-form {
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
            max-width: 300px;
        }

        .change-password-form label {
            display: block;
            margin-bottom: 10px;
        }

        .change-password-form input[type="password"] {
            width: 200px;
            padding: 5px;
        }

        .change-password-form button[type="submit"] {
            padding: 8px 12px;
            background-color: #1d6355;
            border-radius: 5px;
            border: none;
            color: #ffffff;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .change-password-form button[type="submit"]:hover {
            background-color: #00ab97;
        }
.button {
 background-color: #AA0000;
  border: none;
  color: white;
padding:5px; 5px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
border-radius:5%;
font-size:17px;
}
footer {
position:absolute;
bottom: 0;
    width: 100%;
  text-align:center;
color: #00000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete User</h1>

        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <div class="user-table">
            <?php if (count($users) > 0) { ?>
                <table>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="button"  name="delete_user">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No users found.</p>
            <?php } ?>
        </div>

       <br> <h2><center>Change Password</h2></center>
        <form class="change-password-form" method="POST" action="">
            <label for="user_id">User ID:</label>
            <select name="user_id" id="user_id">
                <?php foreach ($users as $user) { ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['id']; ?></option>
                <?php } ?>
            </select>
            <br>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password">
            <br><button type="submit" name="change_password">Change Password</button>
<footer><h3>&copy Admin 2023</h3></footer>        
</form>
    </div>
</body>
</html>

