<?php 
	$conn = include('conn.php');
	
	$sql = "UPDATE User SET Status=Now() WHERE UserID = ".$_SESSION['UserID'].";";
	
	mysqli_query($conn,$sql);
	
?>