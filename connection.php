<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=0.8">
<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
<script src="https://use.fontawesome.com/e8d7e70bd7.js"></script>
<link rel="stylesheet" type="text/css" href="theme.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Login Check</title>
</head>

<body>

<?php

$conn =include('./conn.php');

//If submit is clicked, store the posted values into an appropriate variable and salt for security.
if(isset($_POST['submit']))
{
	$user= htmlspecialchars($_POST['username']);
	$pass= htmlspecialchars($_POST['password']);
	$salt = "qwerty";
	$saltypassword = md5($pass.$salt);
	
$user=str_replace(" ","",$user);
$pass=str_replace(" ","",$pass);

	if (!$user =="" && !$pass ==""){

// Create connection
$conn = include('./conn.php');
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully<br>";

//Checks to see if they match
$sql = "SELECT * FROM User WHERE Username='$user' AND Password='$saltypassword';";
$result = $conn->query($sql);
	
	if($result->num_rows > 0) 
{
	$row = $result->fetch_assoc();
	$_SESSION['UserID'] = $row['UserID'];
	$_SESSION['Username'] = $user;
	
	$priv = $result->fetch_assoc()['Priv']; 
	$_SESSION['Priv'] = $row['Priv'];
	$priv = $_SESSION['Priv'];
	
	If 	($priv==0){
	header ("Location: home.php");}
	If 	($priv==1){
	header ("Location: admin.php");}


	
}
else
	//If user or password don't match with what is stored on the database
{
  echo("
  		<div id='titlebar'>
	<div class='titlename'> 
	mesn
	</div>
	<i id='note' class='fa fa-music' aria-hidden='true'></i>
</div>");
}
}
$conn->close();
}


?>
<br><br><h1>Failed login attempt<br>
<a href="index.php">Click to return</a></h1>

</body>
</html>