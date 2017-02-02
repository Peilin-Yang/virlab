<?php 
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
require_once ("head.html");
require_once ("navigation.php");
?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>addJudge | IR virtual Lab</title>
<script>

$(function() {
$("#createButton").click(function() {
	if($('#combobox-index').val() =='') alert("Please select an index");
	else if(document.forms['addJudge'].judgename.value == '') alert("Please type an evaluation name");
	else if(document.forms['addJudge'].query.value =='') alert("please give us a query file path");
	else if(document.forms['addJudge'].qrel.value =='') alert("please give us a relevant file path");
	else
	{
		$("#confirmWindow").dialog("open");
	}
});

$( "#confirmWindow").dialog({
	autoOpen: false,
	resizable: false,
	height: 200,
	modal: true,
	buttons: {
	  "Create": function() {
		document.forms['addJudge'].theIndex.value = $('#combobox-index').val();
        document.forms['addJudge'].submit();
	},
	Cancel: function() {
		$(this).dialog("close");
	}
	}
});

});

</script>

<?php
function createJudge()
{
	global $mysql;
	global $_POST;

	$collectionName=$_POST['judgename'];
	$theIndexID=$_POST['theIndex'];
	$theQuery=$_POST['query'];
	$theQrel=$_POST['qrel'];
	$query = "select collectionName from collection where collectionName='$collectionName'";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		$phpExMessage.="The evaluation $collectionName is already exist! Try another name. <br/>\n";
		return;
	}
	if(!file_exists($theQuery)) {echo "Please input valid query path!<br/>\n";return;}
	if(!file_exists($theQrel)) {echo "Please input valid evaluation path!<br/>\n";return;}

	$tableName="qry_".$collectionName;
	$query = "create table $tableName (topic char(10),query char(255))";
	if(!$mysql->query($query)) {echo "Could not generate the query database<br/>\n";return;}
	$file=fopen($theQuery,"r") or exit("Unable to open query file!<br/>\n");
	while(!feof($file))
	{
		$line=fgets($file);
		if(empty($line)) break;
		$items=explode(":",$line);
		$topic=$items[0];
		$qry=str_replace("\n","",$items[1]);
		$query = "insert into $tableName values ('$topic','$qry')";
		if(!$mysql->query($query)) {echo "could not insert the query into database<br/>\n";return;}
	}
	fclose($file);
	$tableName="qrel_".$collectionName;
	$query = "create table $tableName (topic char(10),docName char(64),score tinyint)";
	if(!$mysql->query($query)) {echo "Could not generate the qrel database<br/>\n";return;}
	$file=fopen($theQrel,"r") or exit("Unable to open qrel file!<br/>\n");
	while(!feof($file))
	{
		$line=fgets($file);
		if(empty($line)) break;
		$line=str_replace("\n","",$line);
		$items = explode(" ",str_replace("\t"," ",$line));
		$query = "insert into $tableName values ('".$items[0]."','".$items[1]."','".$items[2]."')";
		if(!$mysql->query($mysqlIndex,$query)) {echo "Could not insert the qrel into database<br/>\n";return;}
	}
	fclose($file);
	$query = "insert into collection (indexID,collectionName) values ($theIndexID,'$collectionName')";
	if(!$mysql->query($mysqlIndex,$query)) {echo "Could not generate record<br/>\n";return;}
	echo "Successfully add the evaluation<br/>\n";
}
?>
		
<div id="content">
<?php
	if(isset($_SESSION['user']) && isset($_SESSION['admin']) && $_SESSION['admin']==255)
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n"; 
		
		//$mysqlIndex = mysqli_connect('localhost','usersweb','123456','collections',3666);
		$query="select indexID,indexName from indexes";
		$resultArray = readDatabase($query);

		echo "Choose Collection\n";
		echo "<select id='combobox-index'>\n";
		echo "<option value=''>Select one...</option>\n";
		foreach($resultArray as $result)
		{
			$theName=$result[1];
			$theID = $result[0];
			echo "<option value='$theID'>$theName</option>\n";
		}
		echo "</select><br/>\n";
	
		echo "<form id='addJudge' action='addJudge.php' method='post'>\n";
		echo "Collection Name: <input type='text' name='judgename'><br/>\n";
		echo "Query File: <input type='text' name='query'><br/>\n";
		echo "Qrel File: <input type='text' name='qrel'><br/>\n";
		echo "<input type='hidden' name='theIndex' value=''>\n";
		echo "</form>\n";
		echo "<button id='createButton'>Create</button><br/>\n";
		if(isset($_POST['judgename']) && isset($_POST['theIndex']) && isset($_POST['query']) && isset($_POST['qrel'])) createJudge();
		echo $phpExMessage;
	}
	else
	{
		echo "This toolkit is only for super users, please <a href=\"login.php\">Login</a><br/>\n";
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>

</div>

<?php include("tail.html"); ?>

<div id="confirmWindow" title="Add?">
<p>Are you sure to add the evaluation?</p>
</div>

</body>
</html>
