<!DOCTYPE html>

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

	$EventID = $_GET['EventID'];
	$conn = include('./conn.php');
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT EventName FROM Events WHERE EventID = ?";
	$statement = $conn->prepare($sql);
	$statement->bind_param("i",$EventID);
	$statement->execute();
	$statement->bind_result($name);
	$statement->fetch(); 
	
	
	 echo "$name";
	 $statement->close();
	}
	?>
	</div>
	
	<i id="note" class="fa fa-music" aria-hidden="true"></i>
</div>
<h1>Events</h1>
<a href="home.php">Home</a><br>
<?php 

$sql = "SELECT Count(ReceiveID) as num FROM Message WHERE mRead=0 AND ReceiveID = ". $_SESSION['UserID'].";";
	$result = mysqli_query($conn, $sql);
	$count = $result->fetch_assoc()['num'];
	echo("<a href='inbox.php'>".$count . " New messages</a><br>");

if($_SESSION['Priv'] == 1){
	 echo "<a href='http://www.adamroe.x10host.com/admin.php'>Admin Panel</a><br>";
	 }


if(isset($_GET['suggest']))
{
	$suggestion = htmlspecialchars($_GET['suggestion']);

	$sql = "SELECT BandID from `Bands` WHERE `BandName` = '$suggestion';";

	$result = mysqli_query($conn,$sql);
	$suggestionid = 0;
	if($result->num_rows > 0)
	{
		
		$suggestionid = $result->fetch_assoc()['BandID'];
	}
	$EventID = $_GET['EventID'];
	$date = $_GET['date'];
	//echo("suggest:$suggestion"."event:$EventID"."date:".$date."suggestid:".$suggestionid);
	$sql = "INSERT INTO EventLink(EventID,BandID,Date)  VALUES ($EventID, $suggestionid,'$date')";
	$result = mysqli_query($conn, $sql);
	
	echo ("<script>Band Event Suggested</script>");
}
if(isset($_POST['deletesubmit']))
{
	$rem = $_POST['DeleteEventLinkID'];
	echo "<script>alert('Event Link Removed')</script>";
	$sql = "DELETE FROM UserEventLink WHERE EventLinkID = '$rem'";
	mysqli_query($conn,$sql);
	$sql = "DELETE FROM EventLink WHERE EventLinkID='$rem'";
	$result = mysqli_query($conn, $sql);

}


/*$sql = "SELECT EventName FROM Events WHERE EventID = ?";
$statement = $conn->prepare($sql);
$statement->bind_param("i",$EventID);
$statement->execute();
$statement->bind_result($name);
$statement->fetch(); */


$select = "SELECT Bands.BandID, Bands.BandName, EventLink.`Date`, EventLink.EventLinkID FROM Bands, EventLink WHERE Bands.BandID = EventLink.BandID AND EventLink.EventID = ? AND EventLink.Approved = 1 ORDER BY `Date` DESC";
$stmt = $conn->prepare($select);
$stmt->bind_param("i",$_GET['EventID']);
$stmt->bind_result($BandID, $BandName, $date, $eventlinkid);
$stmt->execute(); 
while($stmt->fetch())
{
	echo "<table><tr><td>$BandName</td><td>Date: $date</td>";
	if($_SESSION['Priv'] == 1){
	echo "<td>";
	echo "<form method='POST' action=''>";
	echo "<input type='hidden' name='DeleteEventLinkID' value='".$eventlinkid."'>";
	echo "<input id='red' type='submit' name='deletesubmit' value='Delete'></form>";
	echo "</td>";
	}
	echo "<td>";
	echo "<form method='GET' action='userband.php'>";
	echo "<input type='hidden' name='EventLinkID' value='".$eventlinkid."'>";
	echo "<input id='green' type='submit' name='entersubmit' value='Enter'></form>";
	echo "</td></tr></table>";
}



/*$result = mysqli_query($conn, $selection);


while ($row = mysqli_fetch_assoc($result)) 

{

echo "<table>";
echo  "<tr>";
echo  "<td>";
echo $row['BandName'];
echo "</td>";
if($_SESSION['Priv'] == 1){
echo "<td><form method='POST' action=''><input type='hidden' name='remove' value='".$row['EventID']."'></td>";
echo  "<td><input id='red' type='submit' name='removesubmit' value='Delete'></td></form>";
}
echo  "<td><form method='POST' action='bands.php'><input type='hidden' name='EventID' value='".$row['EventID']."'><input id='green' type='submit' name='entersubmit' value='Enter'></form></td>";
echo  "</tr>";
echo "</table>";
}
		
		*/
mysqli_close($conn);
?>
<br><br>
Venue Suggestions

<form id="suggest" method="GET" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input list="bands" name="suggestion" placeholder="Enter Band Name">

  
<datalist id="bands">
<?php 
	$conn = include('conn.php');
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
<input type="date" name="date" min="1980-01-01" max="2030-01-01" placeholder="Enter Venue Name" required><br><br>
<input type="hidden" name="EventID" value="<?php echo($_GET['EventID']);?>">
<input type="submit" name="suggest" value="Submit"><br>
</form>



<h3><a href="logout.php">Log out</a></h3>

</body>

</html>