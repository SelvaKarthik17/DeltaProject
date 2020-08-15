
<?php
	require 'dbconfig/config.php';
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Friends</title>
	<meta charset="UTF-8">

</head>

<body>
	<div>

		<a> hello 
			<?php echo $_SESSION['username'] ?>
		</a>

		<form action = "friends.php" method = "POST" enctype="multipart/form-data">
			<label><b>Search:</b></label><br>
			<input name="searchfriends" type="text" class="inputvalues" placeholder="type username to send request" required />
			<input type="submit" name="fsearch" id="submit_btn" value="send request" />
			<a href ="homepage.php"><input type="button" name="returnhome" id="home" value="Return to homepage"/></a>
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

				$input = $_POST['searchfriends'];

				$stmt = "SELECT username from userinfo WHERE username=\"$input\"";
				$query_run = mysqli_query($con,$stmt);

				if($input == $username){
					echo '<script type="text/javascript">alert("invalid")</script>';
				}
				//else if()

				else if(mysqli_fetch_assoc($query_run))
				{		
					$chk = "SELECT rstatus from friends WHERE (user1='$username' AND user2='$input') OR (user2='$username' AND user1='$input')"	;
					$run = mysqli_query($con,$chk);
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
							$query = "INSERT into friends(user1,user2,rstatus) values('$username','$input','1')";
							$qrun = mysqli_query($con,$query);

							
							echo '<script type="text/javascript">alert("Request Sent to \"$input\"")</script>';
 							}


				}	

				else{
					echo '<script type="text/javascript">alert("user not found")</script>';
				}


			}


			$query = "SELECT * from friends WHERE (user1='$username'OR user2='$username' )";
			$qr = mysqli_query($con,$query);

			while ($row = $qr->fetch_assoc())
					 {		//echo "<script type=\"text/javascript\">alert(\"running\")</script>";

					      if($username == $row["user1"]){

					      	$friend = $row["user2"];
					      	$rs = $row["rstatus"];// relationship status

					      		if($rs == 1){
					      				echo "<a>$friend</a><br>
					      				      <a>Friend request sent</a> ";
					      		} 
					      	/*	else if ($rs == 0){
					      			echo "<a>$friend</a><br>
					      				      <a>Not friends</a> ";
					      		}*/
					      		else if($rs == 2){
					      			//echo "<a href=\"chat.php?f=$friend\">$friend</a><br>";
					      			echo "<a href=\"chat.php\">$friend</a><br>";
					      			$_SESSION['chatfriend'] = $friend;
					      			unset($friend);
					      		}



					      }

					      else if($username == $row["user2"]){

					      	$friend = $row["user1"];
					      	$rs = $row["rstatus"];

					      	if($rs == 1){
					      		echo "<a>$friend</a><br>
					      				<form method=\"post\" action=\"\">
        									<input type=\"submit\" name=\"accept\" value=\"Accept\"/>
        									<input type=\"submit\" name=\"discard\" value=\"Discard\"/>
        									<input type=\"hidden\" name=\"frnd\" value=\"$friend\"/>
      								    </form>";				      		

					      	}

	  			      		/*else if ($rs == 0){
					      			echo "<a>$friend</a><br>
					      				      <a>Not friends</a> ";
					      		}*/
					      	else if($rs == 2){
					      			echo "<a href=\"chat.php\">$friend</a><br>";
					      			$_SESSION['chatfriend'] = $friend;
					      			unset($friend);
					      		}



					      }
   				 		

				     }



				     if(isset($_POST['accept']))

				     {
				     		$friend = $_POST['frnd'];
				     		//echo "<script type=\"text/javascript\">alert(\"$friend\")</script>";
				     		$query = "UPDATE friends SET rstatus = '2' WHERE (user1 = '$friend' AND user2 = '$username')";
				     		$qr = mysqli_query($con,$query);
				     }


				     if(isset($_POST['discard']))
				     {
				     		$friend = $_POST['frnd'];
				     		$query = "UPDATE friends SET rstatus = '0' WHERE (user1 = '$friend' AND user2 = '$username')";
				     		$qr = mysqli_query($con,$query);
				     }





						if(isset($_POST['sendrequest']))
						{	
							
							echo "<script type=\"text/javascript\">alert(\"$input\")</script>";
							$query = "INSERT into friends(user1,user2,rstatus) values('$username','$input','1')";
							$qrun = mysqli_query($con,$query);

							
								//echo "<a>$input</a><br>
								  //    <a>Request Sent</a><br>";

						}







		?>

	</div>
</body>
</html>

