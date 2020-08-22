
<?php
	require 'dbconfig/config.php';
	session_start();

	if(!isset($_SESSION['username']))
		{	
			header('location:login.php');
		}
	//header("refresh: 1"); 

	$username = $_SESSION['username'];
	$fid = $_SESSION['chatfriend'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Chat</title>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="mainstyles.css">

</head>

<body>
    
	<div class="wrapper">
        
        
         <div class="sidebar">
        <h2>PROJECT-X</h2>
          <h3 id="uname">hello <?php echo $_SESSION['username'] ?></h3>
        <ul>
            <li><a href="homepage.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="friends.php"><i class="fas fa-user"></i>Friends & Chat</a></li>
            <li><a href="receivedfiles.php"><i class="fas fa-address-card"></i>Files Received</a></li>
 
            <li><a href="logout.php"><i class="fas fa-map-pin"></i>Logout</a></li>
        </ul> 

       </div>
        
        <div class="main_content">

			<form action = "chat.php" method = "POST" enctype="multipart/form-data">
				<input name="msg" type="text" class="inputvalues" placeholder="type message to send" required />
				
				<input type="submit" name="sendmsg" id="submit_btn" value="Send message" />
			<br>
                <br>
			</form>

			<div class="display">
			</div>


           <script
  				src="https://code.jquery.com/jquery-3.5.1.js"
  				integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  				crossorigin="anonymous"></script>
           <script type="text/javascript">


           	setInterval(updater,250);
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

			//echo "<script type=\"text/javascript\">alert(\"$fid\")</script>";

			if(isset($_POST['sendmsg'])){

			$send = $_POST['msg'];

			$query = "INSERT into chat(sender,receiver,message) values(?,?,?)";

            $stmt = mysqli_prepare($con,$query);
            mysqli_stmt_bind_param($stmt,'sss',$username,$fid,$send);
            mysqli_stmt_execute($stmt);
            $query_run = mysqli_stmt_get_result($stmt);


			//$query_run = mysqli_query($con,$stmt);


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
	</div>	

</body>