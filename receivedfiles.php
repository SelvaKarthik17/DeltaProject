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
	<title>Received Files</title>
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

		     $key = $_SESSION['key'];
   			 $username =  $_SESSION['username'];


            $query = "SELECT filename,sender from sharedfiles WHERE receiver=?";

            $stmt = mysqli_prepare($con,$query);
            mysqli_stmt_bind_param($stmt,'s',$username);
            mysqli_stmt_execute($stmt);
            $query_run = mysqli_stmt_get_result($stmt);

			//$query_run = mysqli_query($con,$stmt);

			$i = 0;
			
			$add[0] = 0;
			$typ[0] = 0;
			$nam[0] = 0;
			//echo '<script type="text/javascript">alert("run")</script>';


			while ($row = mysqli_fetch_assoc($query_run)) 
			{				 
				//echo '<script type="text/javascript">alert("run")</script>';
			//echo '<script type="text/javascript">alert("run")</script>';

			//$addr = $row["filedest"] ;
			//$type = $row["filetype"];
			$name = $row["filename"];
            $sender = $row["sender"];
                
			$addr = "received/$username/$name";
			$ext = explode('.', $name);
			$type = end($ext);


			$add[$i] = $addr;
			$nam[$i] = $name;
			$dis[$i] = 1;

			$ad = $add[$i];
			if ( (strcasecmp($type,"png") == 0) || (strcasecmp($type,"jpg") == 0) || (strcasecmp($type,"jpeg")== 0))
			{	

			   			
				//$image = file_get_contents("$ad");

				
				echo "<figure>
                <p>From: $sender</p>
				<img src=$addr width = 100px height= 100px>
			 	<figcaption>$name</figcaption>
			    <form action = \"receivedfiles.php\" method = \"POST\" enctype=\"multipart/form-data\">
				<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				</form>
				</figure><br>";

			}

			else if (strcasecmp($type,"pdf") == 0){

			 //echo '<script type="text/javascript">alert("run")</script>';
				
			 echo "<figure>
                <p>From: $sender</p>
			 	<img src = 'pdflogo.png' width = 100px height= 100px> 
			 	<figcaption>$name</figcaption>
			  <form action = \"receivedfiles.php\" method = \"POST\" enctype=\"multipart/form-data\">
				<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				</form>
				</figure><br> ";
			}
			else if ((strcasecmp($type,"doc") == 0) || (strcasecmp($type,"docx") == 0)){

			
			 echo "<figure>
                 <p>From: $sender</p>
				  <img src = 'doclogo.jpg' width = 100px height= 100px>
				  <figcaption>$name </figcaption>
			 	  <form action = \"receivedfiles.php\" method = \"POST\" enctype=\"multipart/form-data\">
				 	<button type =\"submit\" name=\"$i\">DOWNLOAD</button>
				  </form>
				 </figure><br> ";
				
			}

			else {
				echo "<figure>
                <p>From: $sender</p>
				<img src = 'filelogo.png' width = 100px height= 100px>
				<figcaption>$name </figcaption>
			 	<form action = \"receivedfiles.php\" method = \"POST\" enctype=\"multipart/form-data\">
					<button type =\"submit\" name=\"$i\">DOWNLOAD</button>

				</figure><br> ";			

			}
					
						if(isset($_POST["$i"]))
			{    echo '<script type="text/javascript">alert("rundd")</script>';
				

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




			//echo "<a>$add[$i]</a>";
			$i = $i + 1;
			}

			?>
        </div>
		</div>
	</body>
	</html>



