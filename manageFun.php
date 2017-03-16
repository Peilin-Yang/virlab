<?php 
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
require_once ("head.html");
require_once ("navigation.php");
include_once ("showFun.php");
include_once ("retFun-head.php");
?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>Manage | IR virtual Lab</title>

<script>
var evalTab={};

function eva_click(clicked_id)
{
	document.forms['evaForm'].groupID.value = evalTab[clicked_id];
	document.forms['evaForm'].submit();
}

function del_click(clicked_id)
{
	document.forms['showDelForm'].del.value = evalTab[clicked_id];
	$("#delWindow").dialog("open");
}

function show_click(clicked_id)
{
	document.forms['showDelForm'].show.value = evalTab[clicked_id];
	document.forms['showDelForm'].submit();
}

function edit_click(clicked_id)
{
	document.forms['editForm'].open.value = evalTab[clicked_id];
	document.forms['editForm'].submit();
}

function engine_click(clicked_id)
{
	document.forms['engineForm'].functionID.value = clicked_id;
	document.forms['engineForm'].submit();
}

$(function() {
$( "#delWindow" ).dialog({
	autoOpen: false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Delete": function() {
		document.forms['showDelForm'].submit();
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
	if(isset($_SESSION['user'])) {
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
		echo "<strong>This is the place that you can manage your retrieval functions.</strong><br><br>";
		
		$username=$_SESSION['user'];
                $userID=$_SESSION['userID'];		

		if(isset($_POST['show']) && !empty($_POST['show'])) {
			$groupID=$_POST['show'];
			$query = "select groupName from functionGroup where groupID=$groupID";
			$resultArray=readDatabase($query);
			if(isset($resultArray[0][0])) {
				echo "<h1>". $resultArray[0][0] ."</h1><br/>\n";
				showGroup($groupID);
			}
		}
		else if(isset($_POST['del']) && !empty($_POST['del'])) deleteGroup($_POST['del']);

		$query = "select groupID,functionPath,functionID from function where userID=$userID && onlyFlag=1";
		$resultArray=readDatabase($query);

		if(isset($resultArray[0][0])) {
			echo "<h3> Functions can be used to make search engine! <br/></h3>";
			echo "<table id='tabFunEngine' class='ui-widget ui-widget-content'>\n";
			echo "<thread>\n";
		        echo "<tr class='ui-widget-header'>\n";
			echo "<th>Function ID</th>\n";
		        echo "<th>Retrieval Function</th>\n";
		        echo "<th>Control</th>\n";
		        echo "</tr>\n</thread>\n";
		        echo "<tbody>\n";
			foreach($resultArray as $result) {
				$groupID=$result[0];
				$functionPath=$result[1];
				$functionID=$result[2];
				$functionName="*******";
				if(preg_match('/\/([\w_]+).fun$/',$functionPath,$matches)) {
            		$functionName=$matches[1];
                }
				echo "<script>\n";
		                echo "evalTab['$functionName']=$groupID;\n";
		                echo "</script>\n";

				echo "<tr>\n";
				echo "<td> $functionID </td>\n";
				echo "<td> $functionName </td>\n";
				echo "<td>\n";
				echo "<button id='$functionName' onClick='show_click(this.id)'>SHOW</button>\n";
				echo "<button id='$functionName' onClick='edit_click(this.id)'>EDIT</button>\n";
		                echo "<button id='$functionName' onClick='eva_click(this.id)'>Evaluation</button>\n";
		                echo "<button id='$functionName' onClick='del_click(this.id)'>Delete</button>\n";
				echo "<button id='$functionID' onClick='engine_click(this.id)'> Create Engine </button>\n";
				echo "</td>\n";
			}
			echo "</tbody>\n</table>\n";
		}
		
		echo "<h3> All the functions </h3><br/>\n";
		echo "<table id='tabFun' class='ui-widget ui-widget-content'>\n";
		echo "<thread>\n";
		echo "<tr class='ui-widget-header'>\n";
		echo "<th>Retrieval Function</th>\n";
		echo "<th>Control</th>\n";
		echo "</tr>\n</thread>\n";
		echo "<tbody>\n";
		
		$query= "select groupID,groupName,groupStatus,userID from functionGroup where userID=$userID";
		$resultArray=readDatabase($query);
		foreach($resultArray as $result) {
			$groupID=$result[0];
			$groupName=$result[1];
			$groupStatus=$result[2];
			echo "<script>\n";
			echo "evalTab['$groupName']=$groupID;\n";
			echo "</script>\n";
			echo "<tr>\n";
			if($groupStatus>0) echo "<td>$groupName</td>\n";
			else echo "<td>! $groupName</td>\n";
			echo "<td>\n";
			echo "<button id='$groupName' onClick='show_click(this.id)'>SHOW</button>\n";
			if($result[3]>0) echo "<button id='$groupName' onClick='edit_click(this.id)'>EDIT</button>\n";
			if($groupStatus>0) echo "<button id='$groupName' onClick='eva_click(this.id)'>Evaluation</button>\n";
			if($result[3]>0) echo "<button id='$groupName' onClick='del_click(this.id)'>Delete</button>\n";
			echo "</td></tr>\n";
		}
		echo "</tbody>\n</table>\n";
	}
	else {
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>

<form id='evaForm' action='evaFun.php' method='post'>
<input type='hidden' name='groupID' value=''>
</form>

<form id='editForm' action='addFun.php' method='post'>
<input type='hidden' name='open' value=''>
</form>

<form id='showDelForm' action='manageFun.php' method='post'>
<input type='hidden' name='show' value=''>
<input type='hidden' name='del' value=''>
</form>

<form id='engineForm' action='addEngine.php' method='post'>
<input type='hidden' name='functionID' value=''>
</form>

</div>

<?php include("tail.html"); ?>

<div id="delWindow" title="Delete?">
<p>Are you sure to delete the function?</p>
</div>

</body>
</html>
