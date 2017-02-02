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
	<title>addEngine | IR virtual Lab</title>
<script>

var indexTab={};
var functionTab={};

$(function() {
$("#createButton").click(function() {
	if($('#combobox-index').val() =='' || $('#combobox-retFun').val()=='') alert("Please select an index and retrieval function");
	//else if(document.forms['addEngine'].filename.value == '') alert("Please type a search engine name");
	else
	{
		$("#confirmWindow").dialog("open");
	}
});

$("#showButton").click(function() {
	document.forms['addEngine'].functionID.value = functionTab[$('#combobox-retFun').val()];
	document.forms['addEngine'].indexID.value = indexTab[$('#combobox-index').val()];
	document.forms['addEngine'].theAction.value = "show";
	document.forms['addEngine'].submit();
});

$( "#confirmWindow").dialog({
	autoOpen: false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Create": function() {
		document.forms['addEngine'].indexID.value = indexTab[$('#combobox-index').val()];
		document.forms['addEngine'].functionID.value = functionTab[$('#combobox-retFun').val()];
		document.forms['addEngine'].privacyLevel.value = $('#combobox-privacy').val();
		if(document.forms['addEngine'].filename.value == '') document.forms['addEngine'].filename.value = $('#combobox-retFun').val() + "-" + $('#combobox-index').val();		
		document.forms['addEngine'].theAction.value = "create";
        document.forms['addEngine'].submit();
	},
	Cancel: function() {
		$(this).dialog("close");
	}
	}
});

});

</script>

<?php
function createEngine()
{
	global $_POST;
	global $mysql;
	$filename=$_POST['filename'];
	$functionID=$_POST['functionID'];
	$indexID=$_POST['indexID'];
	$userID=$_SESSION['userID'];
	$privacyLevel=$_POST['privacyLevel'];
	
	$query = "select searchName from searchEngine where userID=$userID and searchName='$filename'";
	$resultArray=readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		echo "The search engine $filename is already exist! Try another name.<br/>\n";
		return;
	}
	$query = "select functionPath from function where functionID=$functionID";
	$resultArray=readDatabase($query);
	if(!isset($resultArray[0][0]))
	{
		echo "Could not locate the retrieval function $functionID<br/>\n";
		return;
	}
	$functionPath=$resultArray[0][0];
	$query = "select indexPath from indexes where indexID=$indexID";
	$resultArray=readDatabase($query);
	if(!isset($resultArray[0][0]))
	{
		echo "Could not locate the index $indexID<br/>\n";
		return;
	}
	$indexPath=$resultArray[0][0];
	
	$enginePath = "users/$userID/engine/$filename";
	mkdir($enginePath);
	mkdir("$enginePath/cache");
	mkdir("$enginePath/snippet");
	$myBasePath="../../../..";
	
	$file = fopen("$enginePath/search.php","w");
	fwrite($file,"<html>\n<body>\n<?php\n");
	fwrite($file,"\$myIndexID=$indexID;\n");
	fwrite($file,"\$myIndexPath='$indexPath';\n");
	fwrite($file,"\$myFunctionID=$functionID;\n");
	fwrite($file,"\$myFunctionPath='../../../../$functionPath';\n");
	fwrite($file,"\$myUserID=$userID;\n");
	fwrite($file,"\$myPrivacyLevel=$privacyLevel;\n");
	fwrite($file,"\$myBasePath='$myBasePath';\n");
	fwrite($file,"include '$myBasePath/source/search-template.php';\n");
	fwrite($file,"?>\n\n</body>\n</html>\n");
	fclose($file);

	$query = "insert into searchEngine (indexID,userID,functionID,searchName,searchPath) values ($indexID,$userID,$functionID,'$filename','$enginePath')";
	$result = $mysql->query($query);

	echo "<br/><b>Create Search Engine $filename success!\n";
	echo "at <a href=\"$enginePath/search.php\"> Link </a></b><br/>\n";

}
?>
		
<?php include "showFun.php" ?>
		
<div id="content">
<?php
	global $mysql;
	if(isset($_SESSION['user']))
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
		echo "<strong>This is the place that you can create search engines.</strong><br><br>";

		$username=$_SESSION['user'];
		$userID=$_SESSION['userID'];

		$query="select indexID,indexName from indexes";
		$resultArray=readDatabase($query);
		echo "Choose Index\n";
			echo "<select id='combobox-index'>\n";
		echo "<option value=''>Select one ... </option>\n";
		foreach($resultArray as $result)
		{
			$indexID=$result[0];
			$indexName=$result[1];
			echo "<script> indexTab['$indexName']=$indexID; </script>\n";
			if(isset($_POST['indexID']) && $_POST['indexID']==$indexID)
			{
				echo "<option value='$indexName' selected>$indexName</option>\n";
			}
			else echo "<option value='$indexName'>".$indexName."</option>\n";
		}
		echo "</select><br/>\n";
		
		$query="select functionID,functionPath from function where userID = $userID && onlyFlag = 1";
		$resultArray=readDatabase($query);
		echo "Choose retrieval function\n";
		echo "<select id='combobox-retFun'>\n";
		echo "<option value=''>Select one...</option>\n";
		foreach($resultArray as $result)
		{
			$functionID=$result[0];
			$functionPath=$result[1];
			$functionName=$functionID;
			if(preg_match('/\/([\w_]+).fun$/',$functionPath,$matches))
			{
				$functionName=$matches[1];
			}
			echo "<script> functionTab['$functionName']=$functionID; </script>\n";
			if(isset($_POST['functionID']) && $_POST['functionID']==$functionID)
			{
				echo "<option value='$functionName' selected>$functionName</option>\n";
			}
			else echo "<option value='$functionName'>$functionName</option>\n";
		}
		echo "</select>\n";
		echo "<button id='showButton'>Show Function</button><br/>\n";

		echo "Privacy Level:\n";
		echo "<select id='combobox-privacy'>\n";
		echo "<option value=1> Level 1: Only yourself can use the search engine </option>\n";
		echo "<option value=2> Level 2: All the users of the system can use the search engine </option>\n";
		echo "</select><br/>\n";

		echo "<form id='addEngine' action='addEngine.php' method='post'>";
		echo "<input type='hidden' name='indexID' value=''>";
		echo "<input type='hidden' name='functionID' value=''>";
		echo "<input type='hidden' name='privacyLevel' value=''>";
		echo "<input type='hidden' name='theAction' value=''>";
		echo "Search Engine Name: <input type='text' name='filename'><br/>";
		echo "</form>";
		echo "<button id='createButton'>Create</button><br/>";

		
		if(isset($_POST['theAction']) && $_POST['theAction']=="show" && isset($_POST['functionID']) && !empty($_POST['functionID'])) 
		{
			$functionID=$_POST['functionID'];
			$query = "select groupID from function where functionID=$functionID";
			$resultArray=readDatabase($query);
			if(isset($resultArray[0][0])) showGroup($resultArray[0][0]);
			else $phpExMessage.="Could not show the function<br/>\n";
		}
		else if(isset($_POST['indexID']) && !empty($_POST['indexID']) && isset($_POST['functionID']) && !empty($_POST['functionID'])) createEngine();
	}
	else
	{
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
		header('Location: login.php');
	}

?>

<?php
	for($i=0;$i<20;$i++) echo "<br/>";
?>

</div>

<?php include("tail.html"); ?>

<div id="confirmWindow" title="Create?">
<p>Are you sure to create the engine?</p>
</div>

</body>
</html>
