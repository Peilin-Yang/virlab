<?php 
require_once ("conf/conf.php");
require_once ("include/superHead.php");
require_once ("include/mysql_connect.php"); 
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Introduction of Virtual IR Lab">
    <link rel="shortcut icon" href="http://www.udel.edu/modules/icons/images/ud.ico">
    <meta name="author" content="Peilin Yang">
    <!--<link rel="shortcut icon" href="../../assets/ico/favicon.ico"> -->

    <title>Login | IR Virtual Lab</title>

    <!-- Bootstrap core CSS -->
    <link href="static/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="static/css/bootstrap-theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/font-awesome/css/font-awesome.min.css">

    <!-- Custom styles for this template -->
    <link href="static/css/theme.css" rel="stylesheet">
  </head>

  <body>
  	<div class="background">
  	  <div class="container">
  	  	<div style="background-color:#0769AD; margin-bottom:20px;">
  	  		<a href="/"><img src="static/img/logo.png"></a>
  	  	</div>

  	  	<div class="row">
  	  		<div class="col-md-3"> </div>
  	  		<div class="col-md-6">
				<?php
				if(isset($_POST["username"]) && isset($_POST["password"]))
				{
					$name=$_POST["username"];
					$password=$_POST["password"];

					if(empty($name) || empty($password))
					{
						echo '<p class="bg-danger text-danger"> *username and password should not be empty</p>';
					}
					else
					{
						//phpinfo();
						$password=sha1(passsalt.$password);
						$query= "select userID,usertype,userGroup from user where loginName = '$name' and password = '$password'";
						$resultArray = readDatabase($query);
						if(isset($resultArray[0][0]))
						{
							if($resultArray[0][1] > 0)
							{
							if($resultArray[0][1] == 255 || $resultArray[0][2] == MyUserGroupID)
							{
							//session_start();
							echo "welcome $name<br/>\n";
							$_SESSION['user']=$name;
							$_SESSION['userID']=$resultArray[0][0];
							$_SESSION['admin']=$resultArray[0][1];
							header( 'Location: home.php' ) ;
							//if($resultArray[0][0] == 255) echo "admin<br/>\n";
							//else echo "user<br/>\n";
							}
							else echo '<p class="bg-danger text-danger"> *You login wrong system! Contact administrator for more details<p/>';
							}
							else echo '<p class="bg-danger text-danger"> *You should wait for the administrator\'s approve to use the system!<p/>';
						}
						else
						{
							echo '<p class="bg-danger text-danger"> *incorrect username or password<p/>';
						}
					}

				}
				?>

				<form role="form" action="login.php" method="post">
				  <div class="form-group">
				    <label for="username">User Name</label>
				    <input type="text" class="form-control" name="username" placeholder="Enter User Name">
				  </div>
				  <div class="form-group">
				    <label for="password">Password</label>
				    <input type="password" class="form-control" name="password" placeholder="Password">
				  </div>
 				  <button type="submit" class="btn btn-default">Log in</button> 
				</form>

				<a href="register.php">Register</a><br/>


			</div>
		</div>
	</div>


    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>

  </body>
</html>
