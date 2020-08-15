<?php 
   session_start();
   require 'dbconfig/config.php';

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>

</head>
<body>
	<div>

		<form action = "login.php" method = "post">
			<label><b>Username:</b></label><br>
			<input name="username" type="text" class="inputvalues" placeholder="Type Username Here"/> <br>
			<label><b>Password:</b></label><br>
			<input name="password" type="Password" class="inputvalues" placeholder="Type Password Here"/> <br>
			<input name="login" type="submit" id="login_btn" value="login"/><br>
			<a href="registration.php"><input type="button" id="register_btn" value="Register"/></a>
		</form>	

		<?php
		 if(isset($_POST['login']))
		 {
		 	$username=$_POST['username'];
		 	$password=$_POST['password'];
		 	$key = null;

		 	$query="select * from userinfo WHERE username='$username' AND password='$password'";

		 	$query_run = mysqli_query($con,$query);

		 	if(mysqli_num_rows($query_run)>0)
		 	{	 
		 	  $query="select sslkey from userinfo WHERE username='$username' ";
		 	  $query_run = mysqli_query($con,$query);

		 	  while ($row = $query_run->fetch_assoc())
				{
   				 foreach($row as $value) $key = $value;
				}

		 	  
		 		//valid , user exists
		 		$_SESSION['username']= $username;
		 		$_SESSION['key']= $key;
		 		header('location:homepage.php');

		 	}
		 	else
		 	{
		 		echo '<script type="text/javascript">alert("Invalid Credentials !")</script>';
		 	}

		 }
 			

		?>

	</div>	
</body>
</html>