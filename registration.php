<?php 

  require 'dbconfig/config.php';

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>

</head>
<body>
	<div>

		<form action = "registration.php" method = "post">
			<label><b>Username:</b></label><br>
			<input name="username" type="text" class="inputvalues" placeholder="Type Username Here" required /> <br>
			<label><b>Fullname:</b></label><br>
			<input name="fullname" type="text" class="inputvalues" placeholder="Type your name Here" required /> <br>
			<label><b>Password:</b></label><br>
			<input name="password" type="password" class="inputvalues" placeholder="Type Password Here" required/> <br>
			<label><b>Confirm Password:</b></label><br>
			<input name="cpassword" type="password" class="inputvalues" placeholder="Type Password Here" required/> <br>
			<input name = "submit_btn" type="submit" id="register_btn" value="Register"/><br>
			<a href ="Login.php"><input type="button" id="back_btn" value="Back To Login Page"/></a>

		</form>	
		
		<?php
            if(isset($_POST['submit_btn']))
            {
            	//echo '<script type="text/javascript"> alert("clicked") </script>';

            	$username = $_POST['username'];
            	$password = $_POST['password'];
            	$cpassword = $_POST['cpassword'];
            	$fullname = $_POST['fullname'];

            	if($password==$cpassword)
            	{
            		$query= "select * from userinfo WHERE username='$username'";
            		$query_run = mysqli_query($con,$query);

            		if(mysqli_num_rows($query_run) > 0){

            			echo '<script type="text/javascript"> alert("Username already exists....try another username") </script>';
            		}
            		else
            		{
            			$key = base64_encode(openssl_random_pseudo_bytes(32));

            			$query= "insert into userinfo(username,fullname,password,sslkey) values('$username','$fullname','$password','$key') ";
            			$query_run = mysqli_query($con,$query);

            			if($query_run)
            			{	
            				mkdir("uploads/$username");
            				mkdir("downloadtmp/$username");
            				mkdir("received/$username");

            				echo '<script type="text/javascript"> alert("registration successful....") </script>';
            			}
            			else
            			{
            				echo '<script type="text/javascript">alert("Error")</script>';
            			}
            		}
 

            	}
            	else 
            	{
            		echo '<script type="text/javascript">alert("Password and confirm password do not match")</script>';
            	}


            }
		?>
</body>
</html>