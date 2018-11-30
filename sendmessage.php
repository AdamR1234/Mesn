<?php
	session_start();
	include("updatestatus.php");

		$rID = $_POST['ChatRecipientID'];
		
		$conn = include('conn.php');

		$sql = "INSERT INTO Message(SenderID, ReceiveID, Msg) VALUES(?,?,?)";
		$statement = $conn->prepare($sql);
		$statement->bind_param("iis",$_SESSION['UserID'],$rID,htmlspecialchars($_POST['msg']));
		$statement->execute();

?>