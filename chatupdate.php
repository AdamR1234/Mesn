<?php
		
session_start();
		include("updatestatus.php");
		$conn = include('./conn.php');
	
		$rID = $_POST['rID'];
		$rUsername = $_POST['rUsername'];
		$sql = "SELECT User.Username, User.Userimage,MessageID, SenderID, Msg 
				FROM Message, User 
					WHERE (((SenderID = ? AND ReceiveID = ?) AND Message.MessageID > ?)
					OR ((SenderID = ? AND ReceiveID = ?) 
					AND Message.MessageID > ?))
					AND User.UserID = SenderID
					ORDER BY Message.MessageID ASC";
		$statement = $conn->prepare($sql);
		$statement->bind_param("iiiiii",$_SESSION['UserID'],$rID,$_SESSION['lastmessage'],$rID,$_SESSION['UserID'],$_SESSION['lastmessage']);
		$statement->bind_result($username,$image,$mid, $sid, $message);
		$statement->execute();
		$statement->store_result();
		/*
		$sidArray = array();
		$messageArray = array();
		$midArray = array();
				
		while($statement->fetch())
		{
			$sidArray[] = $sid;
			$messageArray[] = $message;
			$midArray[] = $mid;
		
		}
		if(sizeof($midArray) > 0){ $_SESSION['lastmessage'] = $midArray[sizeof($midArray) - 1]; } 
		
		foreach($sidArray as $key=>$siddata)
		{
			if($siddata == $_SESSION['UserID'])
			{
				echo("<table><tr><td><img class='profilechaticon' src='$image'> ".$_SESSION['Username'].": ");
			}
			else
			{
				echo("<table><tr><td><img class='profilechaticon' src='$image'>".$rUsername.": ");
				$sql = "UPDATE Message 
				SET mRead =1 
				WHERE MessageID = ".$midArray[$key].";";
				$result = mysqli_query($conn,$sql);
			}
							echo($messageArray[$key] . "</td></tr></table><br/>");

		}*/
		
		while($statement->fetch())
		{
			if($mid > $_SESSION['lastmessage'])
			{
				$_SESSION['lastmessage'] = $mid;
			}
			
			
			echo("<table><tr><td><img class='profilechaticon' src='$image'> $username: $message </td></tr></table><br/>");
			
			if(!$sid == $_SESSION['UserID'])
			{
				$sql = "UPDATE Message 
				SET mRead =1 
				WHERE MessageID = ".$midArray[$key].";";
				$result = mysqli_query($conn,$sql);
			}
		
		}
		


?>