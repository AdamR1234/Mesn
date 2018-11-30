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

	 echo $_SESSION['Username']."'s Account Settings";
	}
	?>
	
	</div>
	
	<i id="note" class="fa fa-music" aria-hidden="true"></i>
</div><br>
<a href="home.php">Home</a>

<div id="userimg">
<?php


if (!isset($_SESSION['Username']))
{
	header('Location: index.php');
}
	   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];

	$value = explode('.',$_FILES['image']['name']);
	$file_ext = strtolower(end($value));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"images/".$file_name);
         echo "<script>alert('".$file_name." upload successful');</script>";
      }else{
         print_r($errors);
      }
   }

	$user = $_SESSION['Username'];
		// Create connection
		$conn = include('conn.php');
		session_start();

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
	$sql = "SELECT * FROM User WHERE Username = '$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<img id='profilepic' src='".$row['Userimage']."'>";
    }
} else {
    echo "0 results";
}

	if(isset($_POST['submit']))
{

$imgupdate = "/images/".$file_name;
$sql = "UPDATE User SET Userimage='$imgupdate' WHERE Username = '$user'";
$result = $conn->query($sql);
echo "<script>alert('".$imgupdate."')</script>";
}
	


$conn->close();
		
	?>



<form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" />
         <input type="submit" name="submit"/>
      </form>
	  
</div>


</body>

</html>