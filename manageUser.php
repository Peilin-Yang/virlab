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
	<title>Manage Users | IR virtual Lab</title>
<script>

function delete_click(clicked_id)
{
	document.forms['cForm'].deleteUserID.value = clicked_id;
	document.getElementById("deleteWindowContent").innerHTML="Are you sure the delete the user " + clicked_id + "?";
	$("#deleteWindow").dialog("open");
}

function resetPW_click(clicked_id)
{
	document.forms['cForm'].resetUserID.value = clicked_id;
	document.getElementById("resetPWWindowContent").innerHTML="Are you sure to reset the password of " + clicked_id + "?";
	$("#resetPWWindow").dialog("open");
}

$(function() {
$( "#deleteWindow" ).dialog({
	autoOpen: false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Delete": function() {
		document.forms['cForm'].submit();
	},
	Cancel: function() {
		document.forms['cForm'].deleteUserID.value = "";
		$(this).dialog("close");
	}
	}
});

$( "#resetPWWindow" ).dialog({
	autoOpen: false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Reset": function() {
		document.forms['cForm'].submit();
	},
	Cancel: function() {
		document.forms['cForm'].resetUserID.value = "";
		$(this).dialog("close");
	}
	}
});

});

</script>
<div id="content">
<?php
	if(isset($_SESSION['user']) && isset($_SESSION['admin']) && ($_SESSION['admin']==255 || $_SESSION['admin']==127))
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";

		$userID=$_SESSION['userID'];
		
		if(isset($_POST['resetUserID']) && !empty($_POST['resetUserID'])) resetPassword($_POST['resetUserID']);
		else if(isset($_POST['deleteUserID']) && !empty($_POST['deleteUserID'])) deleteUser($mysql,$mysqlEva,$_POST['deleteUserID']);

		echo "<table id='usersTab' class='ui-widget ui-widget-content' align='center'>\n";
		echo "<thread>\n";
		echo "<tr class='ui-widget-header '>\n";
		echo "<th>loginName</th>\n";
		echo "<th>type</th>\n";
		echo "<th>Group</th>\n";
		echo "<th>first name</th>\n";
		echo "<th>last name</th>\n";
		echo "<th>affiliation</th>\n";
		echo "<th>email</th>\n";
		echo "<th>functionGroup # </th>\n";
		echo "<th>function # </th>\n";
		echo "<th>engine # </th>\n";
		echo "<th>control </th>\n";
		echo "</tr>\n</thread>\n";
		echo "<tbody>\n";
		$query = "select userID,loginName,userType,firstName,lastName,affiliation,email,userGroup from user";
		$resultArray=readDatabase($query);
		foreach($resultArray as $result)
		{
			$theUserID=$result[0];
			$loginName=$result[1];
			$userType=$result[2];
			$firstName=$result[3];
			$lastName=$result[4];
			$affiliation=$result[5];
			$email=$result[6];
			$userGroup=$result[7];
			//if($_SESSION['admin']==255 || $userGroup==MyUserGroupID)
			if($userType==255 || $userGroup==MyUserGroupID)
			{
			$query = "select groupID from functionGroup where userID=$theUserID";
			$resultArray2=readDatabase($query);
			$groupNum=0;
			if(isset($resultArray2[0][0])) $groupNum=count($resultArray2);
			$query = "select functionID from function where userID=$theUserID";
			$resultArray2=readDatabase($query);
			$functionNum=0;
			if(isset($resultArray2[0][0])) $functionNum=count($resultArray2);
			$query = "select searchID from searchEngine where userID=$theUserID";
			$resultArray2=readDatabase($query);
			$searchNum=0;
			if(isset($resultArray2[0][0])) $searchNum=count($resultArray2);
			echo "<tr>\n";
			echo "<td>$loginName</td>\n";
			echo "<td>$userType</td>\n";
			echo "<td>$userGroup</td>\n";
			echo "<td>$firstName</td>\n";
			echo "<td>$lastName</td>\n";
			echo "<td>$affiliation</td>\n";
			echo "<td>$email</td>\n";
			echo "<td>$groupNum</td>\n";
			echo "<td>$functionNum</td>\n";
			echo "<td>$searchNum</td>\n";
			echo "<td>\n";
			echo "<button id=$theUserID onClick='resetPW_click(this.id)'>resetPasswd</button>\n";
			if($userType<=2) echo "<button id=$theUserID onClick='delete_click(this.id)'>Delete</button>\n";
			echo "</td>\n";
			echo "</tr>\n";
			}
		}
		echo "</tbody>\n</table>\n";
		echo $phpExMessage;
	}
	else
	{
		echo "This toolkit is only for administrator, please <a href=\"login.php\">Login</a><br/>\n";
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>


<form id='cForm' action='manageUser.php' method='post'>
<input type='hidden' name='deleteUserID' value=''>
<input type='hidden' name='resetUserID' value=''>
</form>

</div>

<?php include("tail.html"); ?>

<div id="deleteWindow" title="Delete?">
<p id="deleteWindowContent">Are you sure to delete the user?</p>
</div>

<div id="resetPWWindow" title="reset?">
<p id="resetPWWindowContent">Are you sure to reset the password?</p>
</div>

</body>
</html>
