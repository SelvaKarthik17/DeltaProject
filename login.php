<?php 
   session_start();
   require 'dbconfig/config.php';

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
	<div>
       <div class="registerform">
		<form class="rform" action = "login.php" method = "post">
			<input name="username" type="text" class="inputvalues" placeholder="Username:"/> <br>
			<input name="password" type="Password" class="inputvalues" placeholder="Password:"/> <br>
			<input name="login" type="submit" id="login_btn" value="login"/><br>
			<a href="registration.php"><input type="button" id="back_btn" value="Go to registration page"/></a>
		</form>	
       </div>
            

		<?php
		 if(isset($_POST['login']))
		 {
		 	$username=trim(htmlspecialchars($_POST['username']));		 	
		 	$password=trim(htmlspecialchars($_POST['password']));
		 	$key = null;

		 	$query="select * from userinfo WHERE username= ? AND password= ?";

            $stmt = mysqli_prepare($con,$query);
            mysqli_stmt_bind_param($stmt,'ss',$username,$password);
            mysqli_stmt_execute($stmt);
            $query_run = mysqli_stmt_get_result($stmt);

		 	//$query_run = mysqli_query($con,$query);

		 	if(mysqli_num_rows($query_run)>0)
		 	{	 
		 	  $query="select sslkey from userinfo WHERE username=?";

              $stmt = mysqli_prepare($con,$query);
              mysqli_stmt_bind_param($stmt,'s',$username);
              mysqli_stmt_execute($stmt);
              $query_run = mysqli_stmt_get_result($stmt);

		 	 // $query_run = mysqli_query($con,$query);

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