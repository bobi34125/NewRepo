<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the admin user exists
    $query = "SELECT * FROM admins WHERE admin_username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        // Admin user not found, create a new admin user
        $insert_query = "INSERT INTO admins (admin_username, admin_password) VALUES ('$username', '$hashedPassword')";
        mysqli_query($conn, $insert_query);

        // Store admin user session
        $_SESSION['admin_username'] = $username;
        header('Location: admin.php');
    } else {
        // Admin user already exists, display error message
        $error = "Admin username already exists.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
<style>
* {
  box-sizing: border-box;
}
body {
  background-color: #265552;
  height: 100vh;
  display: flex;
width:100%;
  justify-content: center;
  align-items: center;
color:white;
}
.zaglavie
{
font-size:26px;
position:absolute;
text-align: center;
top:0;
}

table, td {
  border:none;
text-align:center;
width:350px;
height:400px;
margin:auto;
}
table {
 background-color: #FBAB7E;
background-image: linear-gradient(62deg, #26552F 0%, #509E5F 100%);
width: 80%;
  max-width: 350px;
  text-align: center;
  margin: auto;
  display: flex;
  flex-direction: column;
border-radius:2%;
}

.button{
display:inline-block;
position:relative;
background-color:#2F9EAB;
font-weight:300px;
border-radius:10%;
font-size:25px;
width:120px;
padding:5px;
color:white;
border:1px solid #000000
}

input[type=text], select, textarea {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;  
resize: vertical;
font-family: Helvetica, 'FontAwesome', sans-serif;
height:23px;
font-size:16px;}
input[type=password], select, textarea {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
font-family: Helvetica, 'FontAwesome', sans-serif;
height:23px;
font-size:16px;}
.userlogin{
color:white;
}
</style>
</head>
<body>
    <table>
        <tr>
            <td>
                <!-- Your HTML form here -->
                <h1><u>Admin Registration</u></h1>
                <form method="POST" action="">
                    <label><u><b>Admin username:</b></u></label><br>
                    <input type="text" name="admin_username" placeholder="Your admin name here"><br><br>
                    <label><u><b>Admin password:</b></u></label><br>
                    <input type="password" name="admin_password" placeholder="*******"><br><br>
                    <input type="submit" class="button" value="Register">
                </form>
                <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
            </td>
        </tr>
    </table>
</body>
</html>