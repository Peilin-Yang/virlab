<?php 
  require_once("include/superHead.php"); 
  require_once ("conf/conf.php");
  $phpExMessage="";
  require_once("include/mysql_connect.php");
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

    <title>Register | IR Virtual Lab</title>

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
  	  		<a href="home.php"><img src="static/img/logo.png"></a>
  	  	</div>

  	  	<div class="row">
  	  		<div class="col-md-3"> </div>
  	  		<div class="col-md-6">
			<?php
			//add javascript for content validation!
      global $mysql;
			if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["repass"]))
			{
				$username=$_POST["username"];
				$password=$_POST["password"];
				$repass=$_POST["repass"];

				if(empty($username) || empty($password))
				{
					echo '<p class="bg-danger text-danger"> *username and password should not be empty</p>';
				}
				else if($password != $repass) echo '<p class="bg-danger text-danger"> *password and re-enter do not match!</p>';
				else
				{
					//phpinfo();
					$query= "select usertype from user where loginName = '$username'";
					$resultArray = readDatabase($query);
					if($resultArray && isset($resultArray[0][0]))
					{
						echo "<p class=\"bg-danger text-danger\"> *The username $username is already exist! Try another!</p>";
					}
					else
					{
						$firstName="";$lastName="";$affiliation="";$email="";
						if(isset($_POST["firstName"])) $firstName=$_POST["firstName"];
						if(isset($_POST["lastName"])) $lastName=$_POST["lastName"];
						if(isset($_POST["affiliation"])) $affiliation=$_POST["affiliation"];
						if(isset($_POST["email"])) $email=$_POST["email"];
						$userGroup = MyUserGroupID;
						$query="insert into user(loginName,password,usertype,firstName,lastName,affiliation,email,userGroup) values('$username',sha1('wh$password'),0,'$firstName','$lastName','$affiliation','$email',$userGroup)";
						$mysql->query($query);
						echo '<p class="bg-info text-info"> *Please wait for the administer to approve your request!</p>';
					}
				}
			}
			?>

				<form role="form" action="register.php" method="post">
				  <div class="form-group">
				    <label for="username">Username *</label>
				    <input type="text" class="form-control" name="username" maxLength="30" placeholder="Enter User Name">
				  </div>
				  <div class="form-group">
				    <label for="password">Password *</label>
				    <input type="password" class="form-control" name="password" placeholder="Password">
				  </div>
				  <div class="form-group">
				    <label for="password">Password Again*</label>
				    <input type="password" class="form-control" name="repass" placeholder="Password Again">
				  </div>
				  <div class="form-group">
				    <label for="firstname">First Name</label>
				    <input type="text" class="form-control" name="firstName" placeholder="">
				  </div>
				  <div class="form-group">
				    <label for="lastname">Last Name</label>
				    <input type="text" class="form-control" name="lastName" maxLength="50" placeholder="">
				  </div>
				  <div class="form-group">
				    <label for="affiliation">Affiliation</label>
				    <input type="text" class="form-control" name="affiliation" maxLength="128" placeholder="">
				  </div>
				  <div class="form-group">
				    <label for="email">Email</label>
				    <input type="text" class="form-control" name="email" maxLength="50" placeholder="">
				  </div>
				  <button type="submit" class="btn
btn-default">Register</button>
				</form>
			</div>
		</div>
	</div>


    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>

  </body>
</html>
