<?php 
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
include_once ("head.html");
include_once ("navigation.php");
?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>Manage Engine | IR virtual Lab </title>

<script>

function delete_click(clicked_id)
{
	document.forms['deleteForm'].deleteSearchID.value = clicked_id;
	$("#deleteWindow").dialog("open");
}

$(function() {
$( "#deleteWindow" ).dialog({
	autoOpen:false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Delete": function() {
		document.forms['deleteForm'].submit();
	},
	Cancel: function() {
		$(this).dialog("close");
	}
	}
});

});


</script>

<?php include "engine-head.php" ?>

<div id="content">
<?php
	if(isset($_SESSION['user']))
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
		echo "This is the place that you can manage your search engines.<br>";

		$username=$_SESSION['user'];
		$userID=$_SESSION['userID'];

		if(isset($_POST['deleteSearchID']) && !empty($_POST['deleteSearchID']))
		{
			deleteEngine($_POST['deleteSearchID']);
			$phpExMessage.="<font color='red'> Successfully deleted the search engine! </font><br/>\n";
		}

		$query= "select indexID,functionID,searchName,searchPath,searchID from searchEngine where userID=$userID";
		$resultArray = readDatabase($query);
		echo "<div id='users-contain' class='ui-widget'>\n";
		echo "<table id='engines' class='ui-widget ui-widget-content'>\n";
		echo "<thead>\n";
		echo "<tr class=\"ui-widget-header \">\n";
		echo "<th>Name</th>\n";
		echo "<th>Index</th>\n";
		echo "<th>RetFun</th>\n";
		echo "<th>URL</th>\n";
		echo "<th>Manage</th>\n";
		echo "</tr>\n</thead>\n";
		echo "<tbody>\n";
		if(isset($resultArray[0][0])) {
			foreach($resultArray as $result) {
				$indexID=$result[0];
				$functionID=$result[1];
				$searchName=$result[2];
				$searchPath=$result[3];
				$searchID=$result[4];
				$query = "select indexName from indexes where indexID=$indexID";
				$resultArray2 = readDatabase($query);
				$indexName = $resultArray2[0][0];
				$query = "select functionPath from function where functionID = $functionID";
				$resultArray2 = readDatabase($query);
				$functionPath = $resultArray2[0][0];
				$functionName = $functionID;
				if(preg_match('/\/([\w_]+).fun$/',$functionPath,$matches)) {
	                $functionName=$matches[1];
	            }
				echo "<tr>\n";
				echo "<td>$searchName</td>\n";
				echo "<td>$indexName</td>\n";
				echo "<td>$functionName</td>\n";
				echo "<td><a href='$searchPath/search.php'>Link</a></td>\n";
				echo "<td><button id=$searchID onClick='delete_click(this.id)'>Delete</button></td>\n";
				echo "</tr>\n";
			}
		}
      	echo "</tbody>\n</table>\n</div>\n";
		echo $phpExMessage;
	}
	else
	{
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>

<form id='deleteForm' action='manageEngine.php' method='post'>
<input type='hidden' name='deleteSearchID' value=''>
</form>


</div>

<?php include("tail.html"); ?>

<div id="deleteWindow" title="Delete?">
<p> Are you sure to delete the search engine? </p>
</div>

</body>
</html>
