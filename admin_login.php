<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'config.php';

// Check if the admin is already logged in
if (isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];

    // Prepare a SQL statement using a parameterized query
    $query = "SELECT * FROM admins WHERE admin_username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the username parameter
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        
        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);

                // Check if the provided password matches the stored password
                if (password_verify($password, $row['admin_password'])) {
                    $_SESSION['admin_username'] = $username;
                    header('Location: admin.php');
                    exit();
                } else {
                    $error = "Invalid admin credentials.";
                }
            } else {
                $error = "Invalid admin credentials.";
            }
        } else {
            $error = "Error in the database query.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $error = "Error in preparing the SQL statement.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <meta charset="UTF-8">    
<style>
* {
  box-sizing: border-box;
}
body {
  background-color: #9d2933 ;
  height: 100vh;
  display: flex;
width:100%;
  justify-content: center;
  align-items: center;
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
background-image: linear-gradient(62deg, #FBAB7E 0%, #F7CE68 100%);
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
background-color:#b33d48;
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


    <h1><u>Admin Login</u></h1>

    <form method="POST" action="">
      <label><u><b>Admin username:</label></b></u>
        <br><input type="text" name="admin_username" placeholder="Your admin name here">

        <br><br><label><u><b>Admin password:</label></b></u>
       <br><input type="password" name="admin_password" placeholder="*******">

        <br><br><input type="submit" class="button" value="Login">
<p>Don't have an Admin account yet? Well better register here</p> <br><a href="admin_register.php" class="button">Register</a></div>
</tr></td></table>
    </form>

    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
</body>
</html>

