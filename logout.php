<?php include("include/superHead.php"); ?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>Autocomplete | jQuery UI</title>
<?php
 include("head.html");
?>

<?php //session_start(); 
include("navigation.php"); ?>

<div id="content">
<?php
	session_destroy();
	echo "successfully logout! GoodBye<br/>\n";
	header('Location: login.php');
	for($i=0;$i<20;$i++) echo "<br/>";
?>

</div>

<?php include("tail.html"); ?>


</body>
</html>
