
<?php
	require 'dbconfig/config.php';
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>HomePage</title>
	<meta charset="UTF-8">

</head>

<body>
	<div>


		<form action = "share.php" method = "POST" enctype="multipart/form-data">
			<label><b>Type username:</b></label><br>
			<input name="searchuser" type="text" class="inputvalues" placeholder="Type username to send file" required />
			<input type="submit" name="send" id="submit_btn" value="Send to user" />
			<a href ="homepage.php"><input type="button" name="returnhome" id="home" value="Return to homepage"/></a>
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





   			 $query = "SELECT sslkey from userinfo WHERE username = '$username' ";
   			 $run = mysqli_query($con,$query);

   			 while ($row = mysqli_fetch_assoc($run)) {

   			 	foreach($row as $value) $skey = $value;// skey = sender's sslkey   			 }

   			 }

   			 $filename = $_SESSION['sendfilename'];

   			 //echo "<script type=\"text/javascript\">alert(\"$filename\")</script>";
   			 if(isset($_POST['send']))
   			 { 	
   			 	$receiver = $_POST["searchuser"];

   			    $query = "SELECT rstatus from friends WHERE (user1 = '$username' AND user2 = '$receiver') OR (user2 = '$username' AND user1 = '$receiver') ";
   			    $run = mysqli_query($con,$query);

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

   			    $query = "SELECT sslkey from userinfo WHERE username = '$receiver' ";
   			    $run = mysqli_query($con,$query);

   			   while ($row = mysqli_fetch_assoc($run)) {

   			 	   foreach($row as $value) $rkey = $value;// rkey = receiver's sslkey   			 }

   			   }


   			   	$msg = file_get_contents("$senddest");  //

				$msg_encrypted = my_encrypt($msg, $rkey);

				$file = fopen("$senddest", 'wb');
				fwrite($file, $msg_encrypted);
				fclose($file); 


				if($result){
					$query = "INSERT into sharedfiles(sender,receiver,filename) values('$username','$receiver','$filename') ";
					 $run = mysqli_query($con,$query);
					 
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
