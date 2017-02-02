<?php

function showGroup($groupID) {
	echo "<textarea class=\"lined\" name='txtarea' rows='10' cols='1200' disabled='disabled'>\n";
	$query = "select groupPath from functionGroup where groupID=$groupID";
	$resultArray = readDatabase($query);
	if(!isset($resultArray[0][0])) echo "could not find the retrieval function\n";
	else {
		echo file_get_contents($resultArray[0][0]);
	}
	echo "</textarea>\n";
}
?>
