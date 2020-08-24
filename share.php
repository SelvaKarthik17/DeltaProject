
<?php
	require 'dbconfig/config.php';
	session_start();

	if(!isset($_SESSION['username']))
		{	
			header('location:login.php');
		}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Share</title>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="mainstyles.css">

</head>

<body>
   
    
	<div class="wrapper">
        
                 <div class="sidebar">
        <h2>PROJECT-X</h2>
          <h3 id="uname">hello <?php echo $_SESSION['username'] ?></h3>
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="friends.php">Friends & Chat</a></li>
            <li><a href="receivedfiles.php">Files Received</a></li>
 
            <li><a href="logout.php">Logout</a></li>
        </ul> 

       </div>
        
       <div class="main_content">

		<form action = "share.php" method = "POST" enctype="multipart/form-data">
			<input name="searchuser" type="text" class="inputvalues" placeholder="Type username to send file" required />
			<input type="submit" name="send" id="submit_btn" value="Send to user" />
			<br>
		</form>	

		<?php

   			 $username =  $_SESSION['username'];
   			 //$filename = $_SESSION['sendfilename'];
   			 $filenamet = "";

   			if(isset($_POST["share"]))

			{
				//echo "<script type=\"text/javascript\">alert(\"hi\")</script>";
				//$_SESSION["sendf$_POST["sendfile"];

				$_SESSION['sendfilename'] = $_POST["sendfile"];


			}


	function my_encrypt($data, $key) {

   	    $encryption_key = base64_decode($key);
    	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    	$encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    	return base64_encode($encrypted . '::' . $iv);
    }



	function my_decrypt($data, $key) {
  	    $encryption_key = base64_decode($key);
   	    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    	return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
	}





   			 $query = "SELECT sslkey from userinfo WHERE username = ? ";

             $stmt = mysqli_prepare($con,$query);
             mysqli_stmt_bind_param($stmt,'s',$username);
             mysqli_stmt_execute($stmt);
             $run = mysqli_stmt_get_result($stmt);

   			 //$run = mysqli_query($con,$query);

   			 while ($row = mysqli_fetch_assoc($run)) {

   			 	foreach($row as $value) $skey = $value;// skey = sender's sslkey   			 }

   			 }

   			 $filename = $_SESSION['sendfilename'];

   			 //echo "<script type=\"text/javascript\">alert(\"$filename\")</script>";
   			 if(isset($_POST['send']))
   			 { 	
   			 	$receiver = $_POST["searchuser"];

   			    $query = "SELECT rstatus from friends WHERE (user1 = ? AND user2 = ?) OR (user2 = ? AND user1 = ?) ";

                $stmt = mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,'ssss',$username,$receiver,$username,$receiver);
                mysqli_stmt_execute($stmt);
                $run = mysqli_stmt_get_result($stmt);

   			    //$run = mysqli_query($con,$query);

   		    	while ($row = mysqli_fetch_assoc($run)) {

   			 	   foreach($row as $value) $rstatus = $value;// rstatus = relationship status   			 }

   			     }

   			    if ($rstatus == 2){

   			 	echo "<script type=\"text/javascript\">alert(\"$filename\")</script>";
   				$mm = file_get_contents("uploads/$username/$filename");

				$msg_decrypted = my_decrypt($mm, $skey);
				$filee = fopen("downloadtmp/$username/$filename", 'x+');

				fwrite ($filee, $msg_decrypted);
				fclose($filee);

				$tempfile = "downloadtmp/$username/$filename";
				$senddest = "received/$receiver/$filename";
				$result = copy($tempfile,$senddest);
				//rename($senddest,$filename);
				unlink($tempfile);

   			    $query = "SELECT sslkey from userinfo WHERE username = ? ";

                $stmt = mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,'s',$receiver);
                mysqli_stmt_execute($stmt);
                $run = mysqli_stmt_get_result($stmt);

   			    //$run = mysqli_query($con,$query);

   			   while ($row = mysqli_fetch_assoc($run)) {

   			 	   foreach($row as $value) $rkey = $value;// rkey = receiver's sslkey   			 }

   			   }


   			   	$msg = file_get_contents("$senddest");  //

				$msg_encrypted = my_encrypt($msg, $rkey);

				$file = fopen("$senddest", 'wb');
				fwrite($file, $msg_encrypted);
				fclose($file); 


				if($result){
					$query = "INSERT into sharedfiles(sender,receiver,filename) values(?,?,?) ";

              		$stmt = mysqli_prepare($con,$query);
                	mysqli_stmt_bind_param($stmt,'sss',$username,$receiver,$filename);
                	mysqli_stmt_execute($stmt);
                	$run = mysqli_stmt_get_result($stmt);

					//$run = mysqli_query($con,$query);
					 
					echo '<script type="text/javascript">alert("file sent successfully !")</script>';

				}

				else{
					echo '<script type="text/javascript">alert("error in sending the file...")</script>';
				}

			}

			else {
				echo '<script type="text/javascript">alert("File Cannot be sent to the said user...")</script>'; // can be sent only if user exists and are friends...
			}



   			 }




		?>
           
        </div>
    </div>
    </body>
</html>

