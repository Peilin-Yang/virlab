<html>
<body>

<?php
require_once (__DIR__. "/../include/superHead.php"); 
require_once (__DIR__. "/../conf/conf.php");
$phpExMessage="";
require_once (__DIR__. "/../include/mysql_connect.php");

$qry = str_replace(" ","_",escapeshellcmd($_GET["qry"]));
$doc = $_GET["doc"];
$docID = $_GET["docID"];
$theIndex = $_GET["index"];

$query = "select indexPath from indexes where indexID = '$theIndex'";
$result = $mysql->query($query);
if(!$result) echo "Could not run query.<br/>\n";
else if($row = $result->fetch_row())
{
	$indexPath=$row[0];
	echo "<h2>".$doc."</h2><br>\n";
	$command = "./showDoc $indexPath $docID $qry";
	passthru($command);
	$result->free();
}

?>

</body>
</html>
