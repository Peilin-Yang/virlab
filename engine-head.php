<?php
function clearEngineCache($searchID)
{
	$query = "select searchPath from searchEngine where searchID=$searchID";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		$thePath=$resultArray[0][0];
		exec("rm $thePath/cache/*");
		exec("rm $thePath/snippet/*");
	}
}

function deleteEngine($searchID) {
	global $mysql;
	clearEngineCache($searchID);
	$query = "select searchPath from searchEngine where searchID=$searchID";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		$thePath=$resultArray[0][0];
		exec("rm -r $thePath");
		$query = "delete from searchEngine where searchID=$searchID";
		$mysql->query($query);
	}
}

?>
