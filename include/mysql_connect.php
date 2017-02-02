<?php
global $phpExMessage;
$mysql = new mysqli("p:".DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
if ($mysql->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  exit;
}

function readDatabase($query)
{
	global $phpExMessage;
	global $mysql;
	$matches = array();
	$dimen=0;
	if(preg_match('/^select (\S+) from/', $query, $matches)) {
		$dimen=substr_count($matches[1], ',')+1;
	}
	else {
		$phpExMessage.="invalidate query for database read: $query<br/>\n";
    return false;
	}
	$result = $mysql->query($query);
	if(!$result)
	{
		$phpExMessage.="Could not run query on the database. $query <br/>\n";
    return false;
	}
	else
	{
		$resultArray=array(array());
		$i=0;
		while($row = $result->fetch_row())
		{
			for($l=0;$l<$dimen;$l++)
			{
				$resultArray[$i][$l]=$row[$l];
			}
			$i++;
		}
		$result->free();
		return $resultArray;
	}
}

?>
