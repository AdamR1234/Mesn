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
	 echo "Welcome: ", $_SESSION['Username'];
	 	include("updatestatus.php");

	}
	
	?>
	</div>
	
	<i id="note" class="fa fa-music" aria-hidden="true"></i>
</div>
<div id="inbox">
<br>
<a href="home.php">Home</a><br>
<?php
$conn = include('conn.php');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Count(ReceiveID) as num FROM Message WHERE mRead=0 AND ReceiveID = ". $_SESSION['UserID'].";";
	$result = mysqli_query($conn, $sql);
	$count = $result->fetch_assoc()['num'];
	echo("<a href='inbox.php'>".$count . " New messages</a>");

?>
</div>
<?php 
if($_SESSION['Priv'] == 1){
	 echo "<a href='http://www.adamroe.x10host.com/admin.php'>Admin Panel</a><br>";
	 }
	
		



	
		$sql = "SELECT DISTINCT SenderID FROM Message WHERE ReceiveID = ? AND mRead = 0";
		$statement = $conn->prepare($sql);
		$statement->bind_param("i",$_SESSION['UserID']);
		$statement->bind_result($sid);
		$statement->execute();
		$userArray = array();
		
		while($statement->fetch())
		{
			$userArray[] = $sid;
		}
		foreach($userArray as $sid2)
		{
		$sql2 = "SELECT User.Username,User.UserID,User.Status FROM User WHERE User.UserID = $sid2 ORDER BY Status DESC;";

			$result = mysqli_query($conn,$sql2);

			if($result->num_rows > 0)
			{
				
				while($row = $result->fetch_assoc())
				{
					$uname = $row['Username'];
					$status = $row['Status'];
					if($status != 1){
					$ustatus = "<p style='color:red'>Offline</p>";
					}else{
					$ustatus = "<p style='color:green'>Online</p>";
					}
					echo "<table>
					<tr><td>".$uname."</td>
					<td>".$ustatus."</td>
					<td>";

					echo("<form method='GET' action='chat.php'>
					<input type='hidden' name='ChatRecipientID' value='".$row['UserID']."'>
					<input type='hidden' name='ChatRecipientUsername' value='".$row['Username']."'>
					<input id='green' type='submit' name='chatsub' value='Chat'></form> ");
				
				
					echo"
					</td>
					</tr>
					</table>";
				}
				
			}
		}
			

	/*
	if($sid == $_SESSION['UserID'])
			{
				echo("<table><tr><td>".$_SESSION['Username'].": ");
			}
			else
			{
				echo("<table><tr><td>".$rUsername.": ");
				$sql = "UPDATE Message 
				SET mRead =1 
				WHERE MessageID = ".$mid.";";
				$result = mysqli_query($conn,$sql);
			}
			echo($message . "</td></tr></table><br/>");
	
	*/

?>

<h3><a href="logout.php">Log out</a></h3>

</body>

</html>