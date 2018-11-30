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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
   

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
	$_SESSION['lastmessage'] = 0;
	echo "Chat with: " . $_GET['ChatRecipientUsername'];
	}
	?>
	</div>

</div>
<div id="nav">
<a href="home.php">Home</a> - 
<?php 
if($_SESSION['Priv'] == 1){
	 echo " <a href='/admin.php'>Admin Panel</a> - ";
	 }


?><a href="logout.php">Log out </a>
</div>
<div id="chatcontainer">
<?php
		$rID = $_GET['ChatRecipientID'];
		$rUsername = $_GET['ChatRecipientUsername'];

?>

</div>
<script>
function executeQuery() {
	  $.ajax({
	    url: 'chatupdate.php',
		data:{rID: '<?php echo($rID);?>',rUsername:'<?php echo($rUsername);?>'},
		type:'POST',
	    success: function(data) {
	    $("#chatcontainer").append(data);
	    $("#chatcontainer").animate({ scrollTop: $('#chatcontainer').prop("scrollHeight")}, 500);
	    }
	  });
}
executeQuery();
setInterval(executeQuery,2000);
</script>
<form method="POST" id="sendmessage">
<input type="text" name="msg" id="msginput">
<input type="hidden" name="ChatRecipientID" value="<?php echo $rID?>">

<input type="submit" id="msgsubmit" name="msgsub" value="Send">
</form>
<script>
	/*$("#sendmessage").submit(function(e){
		e.preventDefault();

		$.ajax({	
			url:'sendmessage.php',
			data:$("#sendmessage").serialize(),
			type:'post',
			success:function(data){
			}
		});
	});
	*/
	$( "#sendmessage" ).submit(function( event ) {
	event.preventDefault();
	$.ajax({
		type: "post",
		url:'sendmessage.php',
		data:$("#sendmessage").serialize(),
		});
		$('#msginput').val('');

	});

</script>
</body>

</html>