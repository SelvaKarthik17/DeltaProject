
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

		<a> hello 
			<?php echo $_SESSION['username'] ?>
		</a>

		<form action = "homepage.php" method = "POST" enctype="multipart/form-data">
			<input type="file" name="file">
			<button type ="submit" name="submit">UPLOAD</button>
			<input type="submit" name="logout" id="logout_btn" value="Log out"/><br>

		</form>
		<form action = "search.php" method = "POST" enctype="multipart/form-data">
			<label><b>Search:</b></label><br>
			<input name="searchdata" type="text" class="inputvalues" placeholder="search files" required />
			<input type="submit" name="search" id="submit_btn" value="search" /><br>
		</form>	

		<form action = "homepage.php" method = "POST" enctype="multipart/form-data">
			<input type="submit" name="friendspage" id="submit_btn" value="Friends & Chat" /><br>
		</form>	

		<form action = "homepage.php" method = "POST" enctype="multipart/form-data">
			<input type="submit" name="files_received" id="submit_btn" value="Files shared with me" /><br>
		</form>	




		<?php

		if(!isset($_SESSION['username']))
		{	
			header('location:login.php');
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

				//echo "<img src=$filedest>";
				//echo "<embed src=$filedest type= application/pdf width= 100% height= 600px /> ";



			}

			//echo "<a>this is !!!!</a>";
			$stmt = "SELECT filename,filedest,filetype from userdata WHERE username='$username'";
			$query_run = mysqli_query($con,$stmt);
			$i = 0;
			//$tt[0] = 'pp';
			
			$add[0] = 0;
			$typ[0] = 0;
			$nam[0] = 0;

			//echo '<script type="text/javascript">alert("run")</script>';
			while ($row = mysqli_fetch_assoc($query_run)) 
			{				 
				
			//echo '<script type="text/javascript">alert("run")</script>';

			$addr = $row["filedest"];
			$type = $row["filetype"];
			$name = $row["filename"];

			$add[$i] = $addr;
			$nam[$i] = $name;
			$dis[$i] = 1;

			$ad = $add[$i];
			if ( (strcasecmp($type,"png") == 0) || (strcasecmp($type,"jpg") == 0) || (strcasecmp($type,"jpeg")== 0))
			{	

			   			
				$image = file_get_contents("$ad");
				$pre = my_decrypt($image, $key);

				//echo '<img src="data:image/png;base64,'.base64_encode($pre).'" width = 100px height= 100px/>';
				//$pre = base64_decode($pre);
				//echo "<a>$pre</a>";
				//echo "<img src=$pre width = 100px height= 100px>";
				/*echo "<form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
				<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				</form>";*/

			//	unset($pre);
				//unset($image);
				
				echo "<figure>
				<img src=$addr width = 100px height= 100px>
			 	<figcaption>$name</figcaption>
			    <form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
				<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				</form>
			    <form action = \"share.php\" method = \"POST\" enctype=\"multipart/form-data\">
			    	<input type= \"hidden\" name = \"sendfile\" value = \"$name\" >
				   <button type =\"submit\" name=\"share\">SHARE</button>
				</form>
				</figure>";
				//$preview = $pre.'jpg';

				
				//echo '<script type="text/javascript">alert("run")</script>';
				/* echo "<figure>
			 	       <img src=\"data:image/jpeg;base64,'.base64_encode($pre).'\" width = 100px height= 100px/>
			 	       <figcaption>$name</figcaption>
				       <form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
						<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
					 </form>
				   </figure> ";*/
			 //echo "$ad";
			 
			}

			else if (strcasecmp($type,"pdf") == 0){

			 //echo '<script type="text/javascript">alert("run")</script>';
				
			 echo "<figure>
			 	<img src = 'pdflogo.png' width = 100px height= 100px> 
			 	<figcaption>$name</figcaption>
			  <form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
				<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				</form>
			    <form action = \"share.php\" method = \"POST\" enctype=\"multipart/form-data\">
			    	<input type= \"hidden\" name = \"sendfile\" value = \"$name\" >
				   <button type =\"submit\" name=\"share\">SHARE</button>
				</form>
				</figure> ";
			}
			else if ((strcasecmp($type,"doc") == 0) || (strcasecmp($type,"docx") == 0)){

			
			 echo "<figure>
				  <img src = 'doclogo.jpg' width = 100px height= 100px>
				  <figcaption>$name </figcaption>
			 	  <form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
				 	<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				  </form>
			    <form action = \"share.php\" method = \"POST\" enctype=\"multipart/form-data\">
			    	<input type= \"hidden\" name = \"sendfile\" value = \"$name\" >
				   <button type =\"submit\" name=\"share\">SHARE</button>
				</form>
				 </figure> ";
				
			}

			else {
				echo "<figure>
				<img src = 'filelogo.png' width = 100px height= 100px>
				<figcaption>$name </figcaption>
			 	<form action = \"homepage.php\" method = \"POST\" enctype=\"multipart/form-data\">
					<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				</form>
				   <input type= \"hidden\" name = \"sendfile\" value = \"$name\" >
				   <button type =\"submit\" name=\"share\">SHARE</button>
				</form>
				</figure> ";			

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
   			     	header('Content-Type: application/octet-stream');
   			     	//header('Content-Type: image/jpeg');
 			    	header('Content-Disposition: attachment; filename='.basename($downfile));
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




			//echo "<a>$add[$i]</a>";
			$i = $i + 1;
			}

			//$_SESSION['filenumbers'] = $i;

			$i = 0;


			if(isset($_POST['download']))
			{
				$file = "doclogo.jpg";
// Quick check to verify that the file exists
				if( !file_exists($file) ) die("File not found");
// Force the download
				header("Content-Disposition: attachment;filename=". basename($file));
				header("Content-Length: " . filesize($file));
				header("Content-Type: application/octet-stream;");
				readfile($file);
			}

			if(isset($_POST['friendspage']))
			{
				header('location:friends.php');
			}

			if(isset($_POST['files_received']))
			{
				header('location:receivedfiles.php');
			}





			//if ()
			if(isset($_POST['logout']))
			{
				session_destroy();
				header('location:login.php');
			}

		?>
	</div>

</body>
</html>