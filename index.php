<!DOCTYPE html>

<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=0.8">

<title>Welcome</title>

<link rel="stylesheet" type="text/css" href="theme.css">
<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">

<script src="https://use.fontawesome.com/e8d7e70bd7.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   
<script>

$(document).ready(function(){
    $("#loginform").click(function(){
			$("#regform").stop();
			$("#note").css('-webkit-transform','rotate('+-360+'deg)'); 
			$("#log").slideDown("slow");
			$("#loginform").animate({width: "50%"});
			$("#reg").slideUp("slow");
			$("#regform").animate({width: "90px"});
    });
	
   $("#regform").click(function(){
			$("#loginform").stop();
			$("#note").css('-webkit-transform','rotate('+360+'deg)'); 
			$("#reg").slideDown("slow");
			$("#regform").animate({width: "50%"});
			$("#log").slideUp("slow");
			$("#loginform").animate({width: "90px"});
    });
 
});

</script>

<?php
$conn = include('conn.php');

session_start();
if (isset($_SESSION['Username']))
{
	header('Location: home.php');
}

if(isset($_POST['submit']))
{
	$user=htmlspecialchars($_POST['user']);
	$pass=htmlspecialchars($_POST['pass']);
	$salt = "qwerty";
	$saltypassword = md5($pass.$salt);

	$user=str_replace(" ","",$user);
	$pass=str_replace(" ","",$pass);

	if (!$user =="" && !$pass ==""){

		// Create connection
		$conn = include('conn.php');#
		session_start();

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		
			$sql = "SELECT * FROM User WHERE Username = '$user'";
	 $result = mysqli_query($conn,$sql);
	$num_rows = $result->num_rows;

	if($num_rows == 0)
	{
		$sql = "INSERT INTO User (Username, Password, Priv, Userimage, Status) VALUES ('$user', '$saltypassword','0','images/userimg82783121.png','0')";
		$result = mysqli_query($conn, $sql);
		echo "<script>alert('New record created successfully')</script>";
	}
	else
	{
		echo "<script>alert('Username unavailable')</script>";

	}
		
		
		$conn->close();
		}
		
		}
	
?>

</head>

<body>

<div id="titlebar">

	<div class="titlename"> 
	music event social network
	</div>
	<i id="note" class="fa fa-music" aria-hidden="true"></i>
</div>

<div id="loginform">
	<h2>
	log in
	</h2>
	
	<form id="log" method="POST" name="Form" action="connection.php">
<input type="text" name="username" placeholder="Username" pattern="^\S+$" required><br><br>
<input type="password" name="password" placeholder="Enter Password" required><br>
<br>
<input type="submit" name="submit" value="Submit Form"><br>
</form>
</div>

<div id="regform">
	<h2>
	register
	</h2>
	
	<form id="reg" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="text" name="user" placeholder="Enter Username" required><br><br>
<input type="password" name="pass" placeholder="Enter Password" required><br><br>
<input type="submit" name="submit" value="Submit Form"><br>
</form>
</div>

</body>

</html>