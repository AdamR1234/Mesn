<!DOCTYPE HTML>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<link rel="stylesheet" type="text/css" href="679.css"/>

<head> Insert A Product </head>
<body>
<?php

	session_start();
//Connect to database 

if(!isset($_SESSION['uid']))  //check if user-name is not given
	{
		echo('you are not authorised to veiw this page, log in with admin account to view it');
		
		//show login page
	include 'login.html';
	}
  $con = include('conn.php');

	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	if ((($_FILES["file"]["type"] == "image/gif")	|| ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 2000000) && in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    if (file_exists("images/" . $_FILES["file"]["name"]))
      {
      echo ($_FILES["file"]["name"] . " already exists. ");
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"], "images/" .$_FILES["file"]["name"]);
      echo ("Stored in: " . "images/" . $_FILES["file"]["name"]);

	$imagelocation = "images/".$_FILES["file"]["name"];
	echo ("<p>image good, now attempting mysql</p>");
	$sql = "INSERT INTO `CurrentStock` (StockID, StockName, StockQuantity, StockDescription, StockImg, StockPrice, LowStockAlert) VALUES ('" .$con->real_escape_string($_POST['StockID'])."', '".$con->real_escape_string($_POST['StockName'])."', '".$con->real_escape_string($_POST['StockQuantity'])."', '".$con->real_escape_string($_POST['StockDescription'])."', 'images/" . $_FILES["file"]["name"]."',  '".$con->real_escape_string($_POST['StockPrice'])."', '".$con->real_escape_string($_POST['LowStockAlert'])."')";
echo ("attempting execute ");
//$stmt->execute();
if ($con->query($sql) === true){
	echo("<p>successfully added to store</p>");
} else {
	echo "error: ".$sql."<br>".$con->error;
}

      }
    }
	
  }
else
  {
  echo ("<p>Invalid file</p>");
  }
	
	echo("<p><a href='javascript:history.back(1);'>Return To Previous Page</a></p>");
?>
</body>
