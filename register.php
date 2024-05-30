<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the user exists
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        // User not found, create a new user
        $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
        mysqli_query($conn, $insert_query);

        // Store user session
        $_SESSION['username'] = $username;
        header('Location: login.php');
    } else {
        // User already exists, display error message
        $error = "Username already exists.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1" />
 <meta charset="UTF-8">	
    <title>User Registration</title>
<style>
* {
  box-sizing: border-box;
}
body {
     background-color:#19594F ;
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
  background-image: linear-gradient(120deg, #F4F4F4 0%, #F1F1F1  100%);
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
background-color:#207E8E;
font-weight:300px;
border-radius:10%;
font-size:25px;
width:120px;
padding:5px;
color:white;
border:1px solid #000000;
cursor:pointer;
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
    <h1>User Registration</h1>

    <form method="POST" action="register.php">
       <br> <label><b><u>Username:</label></b></u>
       <br><br> <input type="text" name="username" placeholder="&#xf007; Your username here...">

        <br><br><label><b><u>Password:</label></b></u>
       <br> <br><input type="password" name="password" placeholder="&#xf023; *******">

        <br><br><input type="submit" class="button" value="Register">
    </form>

    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
</body>
</html>