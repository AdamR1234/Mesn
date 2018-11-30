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

	$EventID = $_GET['EventLinkID'];
	$conn = include('conn.php');
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT EventName FROM Events,EventLink WHERE Events.EventID = EventLink.EventID AND EventLinkID = ?";
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
<h1>Attendants</h1>
<a href="home.php">Home</a><br><br>

<form id="suggest" method="GET" action="userband.php">
<input type="hidden" name="join" value="<?php echo $_SESSION['UserID']?>">
<input type="hidden" name="EventLinkID" value="<?php echo$_GET['EventLinkID'];?>">

<input type="submit" name="joinevent" value="JOIN PAGE"><br>
</form><br><br>
<?php

$sql = "SELECT Count(ReceiveID) as num FROM Message WHERE mRead=0 AND ReceiveID = ". $_SESSION['UserID'].";";
	$result = mysqli_query($conn, $sql);
	$count = $result->fetch_assoc()['num'];
	echo("<a href='inbox.php'>".$count . " New messages</a><br>");


if($_SESSION['Priv'] == 1){
	 echo "<a href='http://www.adamroe.x10host.com/admin.php'>Admin Panel</a><br>";
	 }

if(isset($_GET['join']))
{	
	
	$UserID = $_GET['join'];
	$EventLinkID = $_GET['EventLinkID'];
	$sql = "SELECT * FROM UserEventLink WHERE UserID = $UserID AND EventLinKID = $EventLinkID";
	$num_rows = mysqli_query($conn,$sql)->num_rows;
	if($num_rows == 0)
	{
		$sql = "INSERT INTO UserEventLink(UserID,EventLinkID)  VALUES ($UserID, $EventLinkID)";
		$result = mysqli_query($conn, $sql);
		echo "<script>alert('You have joined this page')</script>";
	}
	else
	{
		echo "<script>alert('You have already joined this page')</script>";

	}

}
if(isset($_GET['deletesubmit']))
{
	$rem = $_GET['DeleteUserEventLinkID'];
	$sql = "DELETE FROM UserEventLink WHERE UserEventID='$rem'";
	$result = mysqli_query($conn, $sql);
	echo "<script>alert('You have removed yourself from this event'".$rem."')</script>";

}


if(isset($_GET['EventLinkID']))
{
	$suggestion = $_GET['EventLinkID'];
	$sql = "SELECT User.Username,User.UserID,User.Status,User.Userimage,UserEventLink.UserEventID FROM User, UserEventLink WHERE User.UserID = UserEventLink.UserID AND UserEventLink.EventLinkID = $suggestion ORDER BY Status DESC;";

	$result = mysqli_query($conn,$sql);
	$suggestionid = 0;
	if($result->num_rows > 0)
	{
		
		while($row = $result->fetch_assoc())
		{
			$uname = $row['Username'];
			//$status = $row['Status'];
			$status = new DateTime($row['Status']);
			$status->add(new DateInterval('PT30S'));
			$dt = new DateTime();

			if($status->format('Y-m-d H:i:s') > $dt->format('Y-m-d H:i:s'))
			{
			$ustatus = "<p style='color:green'>Online</p>";
			}
			else
			{
				$ustatus = "<p style='color:red'>Offline</p>";
			}
			/*if($status != 1){
			}else{

			}*/
			echo "<table>
			<tr><td><img class='profilechaticon' src='".$row['Userimage']."'></td>
			<td>".$uname."</td>
			<td>".$ustatus."</td>
			<td>";
			if($uname == $_SESSION['Username']){
			echo"<form method='GET' action='userband.php'>
			<input type='hidden' name='DeleteUserEventLinkID' value='".$row['UserEventID']."'>
			<input type='hidden' name='EventLinkID' value='".$_GET['EventLinkID']."'>
			<input type='hidden' name='entersubmit' value='".$_GET['entersubmit']."'>
			<input id='red' type='submit' name='deletesubmit' value='Delete'></form>";
			}
			else
			{
				echo("<form method='GET' action='chat.php'>
				<input type='hidden' name='ChatRecipientID' value='".$row['UserID']."'>
				<input type='hidden' name='ChatRecipientUsername' value='".$row['Username']."'>
				<input id='green' type='submit' name='chatsub' value='Chat'></form> ");
			
			}
			echo"
			</td>
			</tr>
			</table>";
		}
		
	}
	
	
}



/*$sql = "SELECT EventName FROM Events WHERE EventID = ?";
$statement = $conn->prepare($sql);
$statement->bind_param("i",$EventID);
$statement->execute();
$statement->bind_result($name);
$statement->fetch(); 


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




<h3><a href="logout.php">Log out</a></h3>

</body>

</html>