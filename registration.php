<?php 

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
		<form class="rform" action = "registration.php" method = "post">
			
			<input name="username" type="text" class="inputvalues" placeholder="Username:" required /> <br>
			<input name="fullname" type="text" class="inputvalues" placeholder="Fullname:" required /> <br>
			<input name="password" type="password" class="inputvalues" placeholder="Password:" required/> <br>
			<input name="cpassword" type="password" class="inputvalues" placeholder="Confirm Password:" required/> <br>
			<input name = "submit_btn" type="submit" id="register_btn" value="Register"/><br>
			<a href ="Login.php"><input type="button" id="back_btn" value="Back To Login Page"/></a>

		</form>	
       </div>
		
		<?php
            if(isset($_POST['submit_btn']))
            {
            	//echo '<script type="text/javascript"> alert("clicked") </script>';

            	$username = trim(htmlspecialchars($_POST['username']));
            	$password = trim(htmlspecialchars($_POST['password']));
            	$cpassword = trim(htmlspecialchars($_POST['cpassword']));
            	$fullname = trim(htmlspecialchars($_POST['fullname']));

            	if($password==$cpassword)
            	{
            		$query= "select * from userinfo WHERE username=?";

                        $stmt = mysqli_prepare($con,$query);
                        mysqli_stmt_bind_param($stmt,'s',$username);
                        mysqli_stmt_execute($stmt);
                        $query_run = mysqli_stmt_get_result($stmt);

            		//$query_run = mysqli_query($con,$query);

            		if(mysqli_num_rows($query_run) > 0){

            			echo '<script type="text/javascript"> alert("Username already exists....try another username") </script>';
            		}
            		else
            		{
            			$key = base64_encode(openssl_random_pseudo_bytes(32));

            			$query= "insert into userinfo(username,fullname,password,sslkey) values(?,?,?,?)";

                              $stmt = mysqli_prepare($con,$query);
                              mysqli_stmt_bind_param($stmt,'ssss',$username,$fullname,$password,$key);
                              $exe = mysqli_stmt_execute($stmt);
                              $query_run = mysqli_stmt_get_result($stmt);
                              
            			//$query_run = mysqli_query($con,$query);

            			if($exe)
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