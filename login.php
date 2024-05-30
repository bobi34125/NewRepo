<?php
// Start the session
session_start();
include 'config.php';

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch hashed password from the database
    $query = "SELECT password FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result === false) {
        die("Database error: " . mysqli_error($conn)); // Check for database query errors
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $storedHashedPassword = $row['password'];

        // Verify the password
        if (password_verify($password, $storedHashedPassword)) {
            // Password is correct, store user session
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            // Password is incorrect
            $error = "Invalid username or password.";
        }
    } else {
        // User not found, display error message
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1" />
 <meta charset="UTF-8">	

    <title>User Login</title>
<style>
* {
  box-sizing: border-box;
}
body {
  background-color: #A9D392;
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
  background: linear-gradient(90deg, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
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
background-image: linear-gradient(to right, #79defc , #ea37f0);
font-weight:300px;
border-radius:10%;
font-size:25px;
width:120px;
padding:5px;
color:white;
border:1px solid #000000;
cursor:pointer;
}
a{
text-decoration:none;
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
    
<h1><center>User Login</center></h1></div>
    <form method="POST" action="login.php">
<br> <label><b><u>Username:</label></b></u>
        <br><input type="text" name="username" placeholder="&#xf007; Your username here...">
<br><br><label><b><u>Password:</label></b></u>
         
         <br><br><input type="password" name="password" placeholder="&#xf023; *******">

        <br> <br><input type="submit" class="button" value="Login"> 
    </form>

    <p>Don't have an account?</p> <br><a href="register.php" class="button">Register</a></div>
</tr></td></table>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
</body>
</html>
