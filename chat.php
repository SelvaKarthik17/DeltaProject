
<?php
	require 'dbconfig/config.php';
	session_start();
	//header("refresh: 1"); 

	$username = $_SESSION['username'];
	$fid = $_SESSION['chatfriend'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Chat</title>
	<meta charset="UTF-8">

</head>

<body>
	<div>


			<form action = "chat.php" method = "POST" enctype="multipart/form-data">
				<label><b>Search:</b></label><br>
				<input name="msg" type="text" class="inputvalues" placeholder="type message to send" required />
				
				<input type="submit" name="sendmsg" id="submit_btn" value="Send" />
			<br>
			</form>
			<form action = "chat.php" method = "POST" enctype="multipart/form-data">
				<input type="submit" name="returnfrnds" id="home" value="Return to friends list" />
			</form>	
			<div class="display">
			</div>


           <script
  				src="https://code.jquery.com/jquery-3.5.1.js"
  				integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  				crossorigin="anonymous"></script>
           <script type="text/javascript">


           	setInterval(updater,1000);
           	function updater()

           	{	//alert('rinn');
           		$.post("chatupdater.php",{sender:'<?php echo $username?>',receiver:'<?php echo $fid?>'},
           			function(data,status)
           			{
           				document.getElementsByClassName('display')[0].innerHTML = data;

           			}





           			)


           	}



           
        </script>

		
		<?php
			$username = $_SESSION['username'];


			//$fid = $_GET['f'];
			$fid = $_SESSION['chatfriend'];
			echo"<a>hello</a> ";
			//echo "<script type=\"text/javascript\">alert(\"$fid\")</script>";
			echo "<a>$fid</a>";

			if(isset($_POST['sendmsg'])){

			$send = $_POST['msg'];

			$stmt = "INSERT into chat(sender,receiver,message) values('$username','$fid','$send') ";
			$query_run = mysqli_query($con,$stmt);


		     }


		     if(isset($_POST['returnfrnds'])){

		     	//unset($_SESSION['chatfriend']);
		     	//unset($fid);
		     	//$_SESSION['chatfriend'] = "";
		     	//$tempp = $_SESSION['chatfriend'];
		     	//echo "<script type=\"text/javascript\">alert(\"$tempp\")</script>";
		     	header('location:friends.php');

		     }




		?>


	</div>	

</body>