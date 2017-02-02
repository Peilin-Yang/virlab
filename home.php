<?php include("include/superHead.php"); ?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>IR virtual Lab</title>
<?php
 include("head.html");
?>

<?php //session_start(); 
include("navigation.php"); ?>

<div id="content">
<?php
	if(isset($_SESSION['user']))
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
                echo "<br/>\n";
		echo "<b><font color='red'>A brief tutorial of the system is available <a href=\"source/tutorial.pdf\">here</a></font></b><br/>\n";

	}
	else
	{
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
		header('Location: login.php');
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>

</div>

<?php include("tail.html"); ?>


</body>
</html>
