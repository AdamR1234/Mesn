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
	$prv = $_SESSION['Priv'];
	$name = $_SESSION['Username'];
	if($prv == 1){
	 echo "Welcome: ", $name;
	include("updatestatus.php");

	 }else{
	 header('Location: index.php');
	 }?>
	</div>
	
	<i id="note" class="fa fa-music" aria-hidden="true"></i>
</div>
<h1><?php echo $name,"'s Admin Panel" ?> </h1>
<h3>Events awaiting approval</h3>
<a href="http://www.adamroe.x10host.com/home.php">Home</a><br><br>
<?php 
$conn = include('./conn.php');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


if(isset($_POST['addsubmit']))
{
	$add = $_POST['add'];
	echo "<script>alert('Venue Approved')</script>";
	
	$sql = "UPDATE Events SET Approved='1' WHERE EventID='$add'";
	$result = mysqli_query($conn, $sql);
	
}
if(isset($_POST['removesubmit']))
{
	$rem = $_POST['remove'];
	echo "<script>alert('Venue Disapproved')</script>";

	$sql = "DELETE FROM Events WHERE EventID='$rem'";
	$result = mysqli_query($conn, $sql);

}

if(isset($_POST['bandaddsubmit']))
{
	$add = $_POST['bandadd'];
	echo "<script>alert('Band Approved')</script>";
	
	$sql = "UPDATE Bands SET Approved='1' WHERE BandID='$add'";
	$result = mysqli_query($conn, $sql);
	
}
if(isset($_POST['bandremovesubmit']))
{
	$rem = $_POST['bandremove'];
	echo "<script>alert('Band Disapproved')</script>";

	$sql = "DELETE FROM Bands WHERE BandID='$rem'";
	$result = mysqli_query($conn, $sql);

}

if(isset($_POST['bandeventaddsubmit']))
{
	$add = $_POST['add'];
	echo "<script>alert('Band Event Approved')</script>";
	
	$sql = "UPDATE EventLink SET Approved='1' WHERE EventLinkID='$add'";
	$result = mysqli_query($conn, $sql);
	
}
if(isset($_POST['bandeventremovesubmit']))
{
	$rem = $_POST['remove'];
	echo "<script>alert('Band Event Disapproved')</script>";

	$sql = "DELETE FROM EventLink WHERE EventLinkID='$rem'";
	$result = mysqli_query($conn, $sql);

}

$selection = "SELECT * FROM Events WHERE Approved = '0'";
$result = mysqli_query($conn, $selection);


while ($row = mysqli_fetch_assoc($result)) 

{

echo "<table><tr><td>";
echo $row['EventName'];
echo "</td>";
echo  "<td>";
echo $row['EventID'];
echo "</td>";
echo "<td><form method='POST' action=''><input type='hidden' name='remove' value='".$row['EventID']."'</td>";
echo  "<td><input id='red' type='submit' name='removesubmit' value='Disapprove'></form></td>";
echo "<td><form method='POST' action=''><input type='hidden' name='add' value='".$row['EventID']."'</td>";
echo  "<td><input id='green' type='submit' name='addsubmit' value='Approve'></form> <br /></td></tr>";
echo "</table>";
}
echo("<br/> <h3> Band Approval</h3><br/>");



$selection = "SELECT * FROM Bands WHERE Approved = '0'";
$result = mysqli_query($conn, $selection);


while ($row = mysqli_fetch_assoc($result)) 

{

echo "<table><tr><td>";
echo $row['BandName'];
echo "</td>";
echo  "<td>";
echo $row['BandID'];
echo "</td>";
echo "<td><form method='POST' action=''><input type='hidden' name='bandremove' value='".$row['BandID']."'</td>";
echo  "<td><input id='red' type='submit' name='bandremovesubmit' value='Disapprove'></form></td>";
echo "<td><form method='POST' action=''><input type='hidden' name='bandadd' value='".$row['BandID']."'</td>";
echo  "<td><input id='green' type='submit' name='bandaddsubmit' value='Approve'></form> <br /></td></tr>";
echo "</table>";
}




echo("<br/> <h3> Band Event Approval</h3><br/>");

$select = "SELECT Bands.BandID, Bands.BandName, EventLink.`Date`, Events.EventName, EventLink.EventLinkID
			FROM Bands, EventLink, Events 
			WHERE Bands.BandID = EventLink.BandID 
			AND EventLink.Approved = 0
			AND Events.EventID = EventLink.EventID;";
$stmt = $conn->prepare($select);
$stmt->bind_result($BandID, $BandName, $date,$EventName, $EventLinkID);
$stmt->execute(); 
while($stmt->fetch())
{

	echo "<table><tr><td>";
	echo $BandName." ". $date;
	echo "</td>";
	echo  "<td>";
	echo $EventName;
	echo "</td>";
	echo "<td><form method='POST' action=''><input type='hidden' name='remove' value='".$EventLinkID."'</td>";
	echo  "<td><input id='red' type='submit' name='bandeventremovesubmit' value='Disapprove'></form></td>";
	echo "<td><form method='POST' action=''><input type='hidden' name='add' value='".$EventLinkID."'</td>";
	echo  "<td><input id='green' type='submit' name='bandeventaddsubmit' value='Approve'></form> <br /></td></tr>";
	echo "</table>";
}







		

		
mysqli_close($conn);
?>





<h3><a href="logout.php">Log out</a></h3>

</body>

</html>