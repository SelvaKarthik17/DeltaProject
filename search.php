
<?php
	require 'dbconfig/config.php';
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>SearchPage</title>
	<meta charset="UTF-8">

</head>

<body>
	<div>

		<a> hello 
			<?php echo $_SESSION['username'] ?>
		</a>

		<form action = "search.php" method = "POST" enctype="multipart/form-data">
			<label><b>Search:</b></label><br>
			<input name="searchdata" type="text" class="inputvalues" placeholder="search files" required />
			<input type="submit" name="search" id="submit_btn" value="search" />
			<a href ="homepage.php"><input type="button" name="returnhome" id="home" value="Return to homepage"/></a>
			<br>
		</form>	



		<?php



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

			$username = $_SESSION['username'];
		    $key = $_SESSION['key'];

			if(isset($_POST['submit'])){
				$file = $_FILES['file'];

				

				$filename = $_FILES['file']['name'];
				$filetmpname = $_FILES['file']['tmp_name'];
				$filesize = $_FILES['file']['size'];
				$fileerror = $_FILES['file']['error'];
				//$filetype = $_FILES['file']['type'];

				$filedest = 'uploads/'.$username.'/'.$filename;

				move_uploaded_file($filetmpname, $filedest);

				$msg = file_get_contents("$filedest");  //

				$msg_encrypted = my_encrypt($msg, $key);

				$file = fopen("$filedest", 'wb');
				fwrite($file, $msg_encrypted);
				fclose($file);   //

				$filetag = $username.'_'.$filename;
				$ext = explode('.', $filename);
				$filetype = end($ext);


				$query= "insert into userdata(filetag,username,filesize,filetype,filename,filedest) values('$filetag','$username','$filesize','$filetype','$filename','$filedest') ";
            	
            	$query_run = mysqli_query($con,$query);


			}

			//echo "<a>this is !!!!</a>";
			$stmt = "SELECT filename,filedest,filetype from userdata WHERE username='$username'";
			$query_run = mysqli_query($con,$stmt);
			$i = 0;
			$tt[0] = 'pp';
			
			$add[0] = 0;
			$typ[0] = 0;
			$nam[0] = 0;

			//for($k = 0; $k < count($nam);$k++) {
			//	$dis[$k] = 1;
			//}

			//echo '<script type="text/javascript">alert("run")</script>';
			while ($row = mysqli_fetch_assoc($query_run)) {				 
			
			//echo '<script type="text/javascript">alert("run")</script>';

			$addr = $row["filedest"] ;
			$type = $row["filetype"];
			$name = $row["filename"];

			$add[$i] = $addr;
			$nam[$i] = $name;
			$typ[$i] = $type;
			$dis[$i] = 1;

			//$ad = $add[$i];
			$i = $i + 1;
			

			}

			$i = $i - 1;

			
			//$_SESSION['filenumbers'] = $i;

			//$i = 0;

			
			echo "<a>$i</a>";

			if(isset($_POST['search']))
			{
				$input = $_POST['searchdata'];

				
   				 for($j = 0; $j < count($nam);$j++) {
   				 	if(stristr("$nam[$j]", "$input")){

   				 		//echo "<script type=\"text/javascript\">alert(\"$nam[$j]\")</script>";
   				 	//$dis[$j] = 1;

   				if ( (strcasecmp($typ[$j],"png") == 0) || (strcasecmp($typ[$j],"jpg") == 0) || (strcasecmp($typ[$j],"jpeg")== 0))
			{	
				//echo "<script type=\"text/javascript\">alert(\"image\")</script>";
				//$image = file_get_contents("$ad");
				//$pre = my_decrypt($image, $key);

				
				echo "<figure>
				<img src=$addr width = 100px height= 100px>
			 	<figcaption>$nam[$j]</figcaption>
			    <form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
				<button type =\"submit\" name=\"$j\">DOWNLOAD</button>
				</form>
				</figure>";
				//$preview = $pre.'jpg';
			 
			}

			else if (strcasecmp($typ[$j],"pdf") == 0){

				//echo "<script type=\"text/javascript\">alert(\"pdf\")</script>";
			 //echo '<script type="text/javascript">alert("run")</script>';
				
			 echo "<figure>
			 	<img src = 'pdflogo.png' width = 100px height= 100px> 
			 	<figcaption>$nam[$j]</figcaption>
			  <form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
				<button type =\"submit\" name=\"$j\">DOWNLOAD</button>
				</form>
				</figure> ";
			}
			else if ((strcasecmp($typ[$j],"doc") == 0) || (strcasecmp($typ[$j],"docx") == 0)){

			//echo "<script type=\"text/javascript\">alert(\"doc\")</script>";
			 echo "<figure>
				  <img src = 'doclogo.jpg' width = 100px height= 100px>
				  <figcaption>$nam[$j]</figcaption>
			 	  <form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
				 	<button type =\"submit\" name=\"$j\">DOWNLOAD</button>
				  </form>
				 </figure> ";
			
		}

			else {
				//echo "<script type=\"text/javascript\">alert(\"$nam[$j]\")</script>";
				echo "<figure>
				<img src = 'filelogo.png' width = 100px height= 100px>
				<figcaption>$nam[$j] </figcaption>
			 	<form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
					<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				</form>
				</figure> ";	}	


   				 	}

   				 	else{
   				 		//echo "<script type=\"text/javascript\">alert(\"No match found...\")</script>";
   				 		$dis[$j] = 0;
   				 	}	

   				 	//else
   				 		//echo '<script type="text/javascript">alert("not found")</script>';
   				 }
				


			}



			
									if(isset($_POST["$i"]))
			{    echo '<script type="text/javascript">alert("run")</script>';
				

				$mm = file_get_contents("$add[$i]");

				$msg_decrypted = my_decrypt($mm, $key);
				$filee = fopen("downloadtmp/$username/$nam[$i]", 'x+');
				//$filee = fopen("downloadtmp/tmpry.png", 'x+');
				fwrite ($filee, $msg_decrypted);
				fclose($filee);

				$downfile = "downloadtmp/$username/$nam[$i]";

// Quick check to verify that the file exists
				if( !file_exists($downfile) ) die("File not found");

// Force the download
				if (file_exists($downfile)) {
    				header('Content-Description: File Transfer');
   			     	//header('Content-Type: application/octet-stream');
   			     	header('Content-Type: image/jpeg');
 			    	header('Content-Disposition: attachment; filename='.basename($downfile));
 			    	//header("Content-Disposition: attachment; filename=$downfile");
    				header('Content-Transfer-Encoding: binary');
    				header('Expires: 0');
    				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    				header('Pragma: public');
    				header('Content-Length: ' . filesize($downfile));
    				ob_clean();
    				flush();
    				readfile($downfile);
    				unlink($downfile);
    				exit;
				}
		
			}



		?>
	</div>

</body>
</html>
