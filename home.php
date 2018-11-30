<!DOCTYPE html>

<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=0.8">

<title>Welcome</title>

<link rel="stylesheet" type="text/css" href="theme.css">
<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Julius+Sans+One" rel="stylesheet">
<script src="https://use.fontawesome.com/e8d7e70bd7.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   

</head>

<body>

<div id="titlebar">
	<div class="titlename"> 
	<?php 
	session_start();
	if (!isset($_SESSION['Username']))
	{
		header('Location: index.php');
	}else{
		include("updatestatus.php");

	 echo "Welcome: ", $_SESSION['Username'];
	}
	?>
	</div>
	
	<i id="note" class="fa fa-music" aria-hidden="true"></i>
</div>
<div id="inbox">
<br>
<?php
$conn = include('./conn.php');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Count(ReceiveID) as num FROM Message WHERE mRead=0 AND ReceiveID = ". $_SESSION['UserID'].";";
	$result = mysqli_query($conn, $sql);
	$count = $result->fetch_assoc()['num'];
	echo("<a href='inbox.php'>".$count . " New messages</a><br>");

?>
<a href="profile.php">Account Info</a>
</div>
<?php 
if($_SESSION['Priv'] == 1){
	 echo "<a href='http://www.adamroe.x10host.com/admin.php'>Admin Panel</a><br>";
	 }


if(isset($_POST['suggest']))
{
	$suggestion = htmlspecialchars($_POST['suggestion']);
	$sql = "INSERT INTO Events (EventName) VALUES ('$suggestion')";
	$result = mysqli_query($conn, $sql);
}
if(isset($_POST['suggestsubmit']))
{
	$sug = htmlspecialchars($_POST['sug']);
	$sql = "INSERT INTO Bands (BandName) VALUES ('$sug')";
	$result = mysqli_query($conn, $sql);
}
if(isset($_POST['removesubmit']))
{
	$rem = $_POST['remove'];
	echo "<script>alert('Venue Removed')</script>";

	$sql = "DELETE FROM Events WHERE EventID='$rem'";
	$result = mysqli_query($conn, $sql);

}


$selection = "SELECT * FROM Events WHERE Approved = '1'";
$result = mysqli_query($conn, $selection);


while ($row = mysqli_fetch_assoc($result)) 

{

echo "<table>";
echo  "<tr>";
echo  "<td>";
echo $row['EventName'];
echo "</td>";
if($_SESSION['Priv'] == 1){
echo "<form method='POST' action=''><input type='hidden' name='remove' value='".$row['EventID']."'>";
echo  "<td><input id='red' type='submit' name='removesubmit' value='Delete'></td></form>";
}
echo  "<td><form method='GET' action='bands.php'><input type='hidden' name='EventID' value='".$row['EventID']."'><input id='green' type='submit' name='entersubmit' value='Enter'></form></td>";
echo  "</tr>";
echo "</table>";
}
		
		
mysqli_close($conn);
?>

<br><br>
Venue Suggestions

<form id="suggest" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="text" name="suggestion" placeholder="Enter Venue Name" required><br><br>
<input type="submit" name="suggest" value="Submit"><br>
</form>
<br><br>
Band Suggestions
<!--<form id="suggest" method="POST" action="<?php// echo $_SERVER['PHP_SELF'];?>">
<input type="text" name="sug" placeholder="Enter Band Name" required><br><br>
<input type="submit" name="suggestsubmit" value="Submit"><br>
</form>-->

<form id="suggest" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input list="bands" name="sug" placeholder="Enter Band Name \(That isn't already in the dropdown list\)">

  
<datalist id="bands">
<?php 
	$servername = "localhost";
	$username = "adamroex_ok";
	$password = "Turnip10";
	$dbname = "adamroex_roe";


		
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	$sql = "SELECT BandID, BandName FROM Bands";
	$result = mysqli_query($conn,$sql);
	while($row = $result->fetch_assoc())
	{
		$bandid = $row['BandID'];
		$bandname = $row['BandName'];
		echo("<option value='$bandname'>$bandname</option>");

	}
?>
</datalist>
<input type="submit" name="suggestsubmit" value="Submit"><br>
</form>

<h3><a href="logout.php">Log out</a></h3>

</body>

</html>