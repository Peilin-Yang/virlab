<?php 
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
require_once ("head.html");
require_once ("navigation.php");
include_once ("retFun-head.php");
?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>Add Function | IR virtual Lab</title>

<script src="codemirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="codemirror/lib/codemirror.css">
<script src="codemirror/mode/javascript/javascript.js"></script>
<script src="codemirror/mode/clike/clike.js"></script>
<link type="text/css" rel="stylesheet" href="codemirror/theme/eclipse.css">
<link href="./css/p.css" rel="stylesheet" media="screen">

<script>

var h={};
var typeTab={};

$(function() {

$( "#openButton" ).click(function() {
	if($('#combobox-retFun').val() !='')
	{
		document.forms['retFun-OD'].open.value=h[$('#combobox-retFun').val()];
		document.forms['retFun-OD'].submit();
	}
});

$( "#delButton" ).click(function() {
	if($('#combobox-retFun').val() !='')
	{
		var selectedItem = h[$('#combobox-retFun').val()];
		if(typeTab[$('#combobox-retFun').val()] == 255) alert("Default function could not be deleted!");
		else $("#delWindow").dialog("open");
	}
});

$( "#saveButton" ).click(function() {
	var saveFilename=document.forms['retFun-editor'].filename.value
	if(saveFilename == '') alert("Filename should not be empty!");
	else
	{
		if(h[saveFilename] != undefined)
		{
			if(typeTab[saveFilename] == 255) alert("Default function could not be overwrite!");
			// else $("#saveWindow").dialog("open");
			else alert("This function name has been used. Please select a new one.");
		}
		else
		{
			document.forms['retFun-editor'].submit();
		}
	}
});

$( "#saveWindow" ).dialog({
	autoOpen: false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Overwrite": function() {
		document.forms['retFun-editor'].submit();
	},
	Cancel: function() {
		$(this).dialog( "close" );
	}
	}
});

$( "#delWindow" ).dialog({
	autoOpen: false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Delete": function() {
		document.forms['retFun-OD'].del.value=h[$('#combobox-retFun').val()];
		document.forms['retFun-OD'].submit();
	},
	Cancel: function() {
		$(this).dialog( "close" );
	}
	}
});

var myCodeMirror = CodeMirror.fromTextArea(func_txt, {
  mode:  "text/x-c++src",
  theme: "eclipse",
  lineNumbers: "true",
});

});
</script> 

<div id="content">
<?php
	//session_start();
	if(isset($_SESSION['user']))
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
		echo "<strong>This is the place that you can create retrieval function.</strong><br><br>";
	
		$username=$_SESSION['user'];
		$userID=$_SESSION['userID'];

		if(isset($_POST["filename"]) && isset($_POST["txtarea"])) {
			if(preg_match('/^[\w_]+$/',$_POST["filename"])) {
				saveFunctionGroup($userID,$_POST["txtarea"],$_POST["filename"]);
			}
			else {
				$phpExMessage.="Error: Invalid function name: the function name should only contain letter, '_' and numbers.<br/>\n";
			}
		}
    	else if(isset($_POST["del"]) && !empty($_POST["del"])) deleteGroup($_POST["del"]);

		$displayFilename = "";
		$displayTextarea = "";
		if(isset($_POST['open']) && !empty($_POST['open'])) {
			$theGroupID = $_POST['open'];
			$query = "select groupName,groupPath from functionGroup where groupID=$theGroupID";
			$resultArray=readDatabase($query);
			if(!isset($resultArray[0][0])) {
				$displayTextarea = "Could not find the retrieval function group\n";
			}
			else {
				$displayTextarea = file_get_contents($resultArray[0][1]);
				$displayFilename = $resultArray[0][0];
			}
		}
		else if(isset($_POST['txtarea'])) {
			$displayTextarea = $_POST['txtarea'];
			$displayFilename = $_POST['filename'];
		}

		$query = "select groupID,groupName,groupStatus,userID from functionGroup where userID=$userID || userID=0";
		$resultArray=readDatabase($query);
		echo "<select id='combobox-retFun'>\n";
		echo "<option value=''>Select one ...</option>\n";
		foreach($resultArray as $result) {
			$retFunOption=$result[1];
	        // Hide BM25 as Dr.Fang required, Peilin.
	        //if ($retFunOption == "BM25") {
	          //continue;
	        //}
			$retFunStatus=$result[2];
			$retFunID=$result[0];
			echo "<script>\n";
			echo "h['$retFunOption']=$retFunID;\n";
			if($result[3]>0) echo "typeTab['$retFunOption']=0;\n";
			else echo "typeTab['$retFunOption']=255;\n";
			echo "</script>\n";
			echo "<option value='$retFunOption'>";
			if($retFunStatus==0) echo "! $retFunOption";
			else echo $retFunOption;
			echo "</option>\n";
		}
		echo "</select>\n";
		echo "<button id='openButton'>OPEN</button>\n";
		//echo "<button id='delButton'>DELETE</button><br/>\n";

		echo "<form id='retFun-editor' action='addFun.php' method='post'><br/>\n";
		echo "Function name: <input type='text' name='filename' value=$displayFilename ><br/>\n";
		echo "Content : <textarea id=\"func_txt\" name='txtarea' rows='20' cols='200' style='width: 600px;'>\n";
		echo $displayTextarea;
		echo "</textarea>\n";
		echo "</form>\n";

		echo "<div class=\"view-source\"><a tabindex=\"0\"> view help</a><div><div class=\"syntaxhighlighter xml \">\n";
		echo "<table><tbody><tr>\n";
		include ("stat-help.html");
		echo "</tr></tbody></table></div></div></div>\n";
		echo "<button id='saveButton'>Save</button><br/>\n";
		echo "<font color='red'>$phpExMessage</font>";
		
	}
	else
	{
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
		header('Location: login.php');
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>

<form id='retFun-OD' action='addFun.php' method='post'>
<input type='hidden' name='open' value=''>
<input type='hidden' name='del' value=''>
</form>

</div>

<?php include("tail.html"); ?>

<div id="saveWindow" title="Overwrite?">
<p>Are you sure to overwrite the function?</p>
</div>

<div id="delWindow" title="Delete?">
<p>Are you sure to delete the function?</p>
</div>

</body>
</html>
