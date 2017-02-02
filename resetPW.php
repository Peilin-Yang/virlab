<?php 
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
require_once ("head.html");
require_once ("navigation.php");
include_once ("user-head.php");
?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>IR virtual Lab</title>

<script>

$(function() {

$( "#resetButton" ).click(function() {
	if(document.forms['password'].oldPW.value == '') alert("Please enter the old password!");
	else if(document.forms['password'].newPW.value == '') alert("The new password should not be empty");
	else if(document.forms['password'].newPW.value != document.forms['password'].retypePW.value) alert("The re-type password is not the same!");
	else $("#resetWindow").dialog("open");
});

$( "#resetWindow" ).dialog({
	autoOpen: false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Reset": function() {
		document.forms['password'].submit();
	},
	Cancel: function() {
		$(this).dialog( "close" );
	}
	}
});

});

</script>

<div id="content">
<?php
	if(isset($_SESSION['user']))
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";

		$userID=$_SESSION['userID'];

		if(isset ($_POST['oldPW']) && !empty($_POST['oldPW']) && isset($_POST['newPW']) && !empty($_POST['newPW'])) changePassword($userID,$_POST['oldPW'],$_POST['newPW']);

		echo "<form id='password' action='resetPW.php' method='post'>\n";
		echo "Old &nbsp&nbsp&nbsp&nbsp&nbsp Password *<input type='password' name='oldPW' /><br/>\n";
		echo "New &nbsp&nbsp&nbsp Password *<input type='password' name='newPW' /><br/>\n";
		echo "re-type Password *<input type='password' name='retypePW' /><br/>\n";
		echo "</form>\n";
		echo "<button id='resetButton'> CHANGE </button>\n";

		echo $phpExMessage;
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

<div id="resetWindow" title="reset password?">
<p> Are you sure to reset the password? </p>
</div>

</body>
</html>
