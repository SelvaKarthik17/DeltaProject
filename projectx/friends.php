
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
	<title>Friends</title>
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

		<form id= "sform" action = "friends.php" method = "POST" enctype="multipart/form-data">
			<input name="searchfriends" type="text" class="inputvalues" placeholder="Find friends" required />
			<input id="sbutn" type="submit" name="fsearch" id="submit_btn" value="send request" />

			<br>
		</form>	

		<?php
			$_SESSION['chatfriend'] = null;
			$username = $_SESSION['username'];
			//$input = "0";
			//

			if(isset($_POST['fsearch']))
			{	
				global $input;

				$input = trim(htmlspecialchars($_POST['searchfriends']));

				$sql = "SELECT username from userinfo WHERE username=?";
				$stmt = mysqli_prepare($con,$sql);
				mysqli_stmt_bind_param($stmt,'s',$input);
				mysqli_stmt_execute($stmt);

				$query_run = mysqli_stmt_get_result($stmt);


				if($input == $username){
					echo '<script type="text/javascript">alert("invalid")</script>';
				}
				//else if()

				else if(mysqli_fetch_assoc($query_run))
				{		
					$chk = "SELECT rstatus from friends WHERE (user1=? AND user2=?) OR (user2=? AND user1=?)"	;

					$stmt = mysqli_prepare($con,$chk);
					mysqli_stmt_bind_param($stmt,'ssss',$username,$input,$username,$input);
					mysqli_stmt_execute($stmt);
					$run = mysqli_stmt_get_result($stmt);

					//$run = mysqli_query($con,$chk);
					$status = null;

					  while ($row = $run->fetch_assoc())
					 {
   				 		foreach($row as $value) $status = $value;
				     }

				     if($status == 1){
				     		echo '<script type="text/javascript">alert("request already sent...")</script>';
				     }
				     else if ($status == 2){
				     	echo '<script type="text/javascript">alert("You are already friends with the user...")</script>';
				     }

					 else {
							echo "<script type=\"text/javascript\">alert(\"$input\")</script>";
							$query = "INSERT into friends(user1,user2,rstatus) values(?,?,'1')";

							$stmt = mysqli_prepare($con,$query);
							mysqli_stmt_bind_param($stmt,'ss',$username,$input);
							mysqli_stmt_execute($stmt);
							$qrun = mysqli_stmt_get_result($stmt);


							//$qrun = mysqli_query($con,$query);
							
							echo '<script type="text/javascript">alert("Request Sent to \"$input\"")</script>';
 							}


				}	

				else{
					echo '<script type="text/javascript">alert("user not found")</script>';
				}


			}


			$query = "SELECT * from friends WHERE (user1= ? OR user2= ?)";
			//$qr = mysqli_query($con,$query);

			$stmt = mysqli_prepare($con,$query);
			mysqli_stmt_bind_param($stmt,'ss',$username,$username);
			mysqli_stmt_execute($stmt);
			$qr = mysqli_stmt_get_result($stmt);

			$friend[0] = "";
			$k = 0;
              echo "<br><p id=\"urfnds\">Your Friends : </p><br>";
              
			while ($row = $qr->fetch_assoc())
					 {		//echo "<script type=\"text/javascript\">alert(\"running\")</script>";

                            
					      if($username == $row["user1"]){

					      	$friend[$k] = $row["user2"];
					      	$rs = $row["rstatus"];// relationship status

					      		if($rs == 1){
					      				echo "<a>$friend[$k]</a><br>
					      				      <a>Friend request sent</a> <br>";
					      		} 
					      	/*	else if ($rs == 0){
					      			echo "<a>$friend</a><br>
					      				      <a>Not friends</a> ";
					      		}*/
					      		else if($rs == 2){
					      			//echo "<a href=\"chat.php?f=$friend\">$friend</a><br>";
					      			//echo "<a href=\"chat.php\">$friend[$k]</a><br>";

					      		    echo "<a>$friend[$k]</a><br>
					      				<form method=\"post\" action=\"\">     									
        									<input type=\"hidden\" name=\"frd\" value=\"$friend[$k]\"/>
        									<input type=\"submit\" name=\"chat\" value=\"Click to chat\"/>
      								    </form><br>";				      		


					      			//$_SESSION['chatfriend'] = $friend[$k];
					      			//unset($friend);
					      		}



					      }

					      else if($username == $row["user2"]){

					      	$friend[$k] = $row["user1"];
					      	$rs = $row["rstatus"];

					      	if($rs == 1){
					      		echo "<a>$friend[$k]</a><br>
					      				<form method=\"post\" action=\"\">
        									<input type=\"submit\" name=\"accept\" value=\"Accept\"/>
        									<input type=\"submit\" name=\"discard\" value=\"Discard\"/>
        									<input type=\"hidden\" name=\"frnd\" value=\"$friend[$k]\"/>
      								    </form><br>";				      		

					      	}

	  			      		/*else if ($rs == 0){
					      			echo "<a>$friend</a><br>
					      				      <a>Not friends</a> ";
					      		}*/
					      	else if($rs == 2){
					      			//echo "<a href=\"chat.php\">$friend[$k]</a><br>";

					      		    echo "<a>$friend[$k]</a><br>
					      				<form method=\"post\" action=\"\">     									
        									<input type=\"hidden\" name=\"frd\" value=\"$friend[$k]\"/>
        									<input type=\"submit\" name=\"chat\" value=\"Click to chat\"/>
      								    </form><br>";				      		

					      			//$_SESSION['chatfriend'] = $friend[$k];
					      			//unset($friend);
					      		}



					      }
   				 		
					      $k = $k + 1;
				     }



				     if(isset($_POST['accept']))

				     {
				     		$friend = $_POST['frnd'];
				     		//echo "<script type=\"text/javascript\">alert(\"$friend\")</script>";
				     		$query = "UPDATE friends SET rstatus = '2' WHERE (user1 = ? AND user2 = ?)";

							$stmt = mysqli_prepare($con,$query);
							mysqli_stmt_bind_param($stmt,'ss',$friend,$username);
							mysqli_stmt_execute($stmt);
							$qr = mysqli_stmt_get_result($stmt);

				     		//$qr = mysqli_query($con,$query);
				     }


				     if(isset($_POST['discard']))
				     {
				     		$friend = $_POST['frnd'];
				     		$query = "UPDATE friends SET rstatus = '0' WHERE (user1 = ? AND user2 = ?)";

							$stmt = mysqli_prepare($con,$query);
							mysqli_stmt_bind_param($stmt,'ss',$friend,$username);
							mysqli_stmt_execute($stmt);
							$qr = mysqli_stmt_get_result($stmt);

				     		//$qr = mysqli_query($con,$query);
				     }


				     if(isset($_POST['chat']))
				     {
				     	$_SESSION['chatfriend'] = $_POST['frd'];
				     	header('location:chat.php');
				     }





						if(isset($_POST['sendrequest']))
						{	
							
							echo "<script type=\"text/javascript\">alert(\"$input\")</script>";
							$query = "INSERT into friends(user1,user2,rstatus) values(?,?,'1')";

							$stmt = mysqli_prepare($con,$query);
							mysqli_stmt_bind_param($stmt,'ss',$username,$input);
							mysqli_stmt_execute($stmt);
							$qrun = mysqli_stmt_get_result($stmt);

							//$qrun = mysqli_query($con,$query);

							
								//echo "<a>$input</a><br>
								  //    <a>Request Sent</a><br>";

						}







		?>
        </div>
	</div>
</body>
</html>

