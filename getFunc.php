<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
	if ((isset($_GET["uid"]) && strlen($_GET["uid"])>0) && 
		(isset($_GET["fid"]) && strlen($_GET["fid"])>0)){
		$uid = $_GET["uid"];
		$function_id = $_GET["fid"];
		$mysql = mysqli_connect('headnode','usersweb','3Lu9PapFHUapaJVd','users2',3666);
		if(!$mysql) echo "";
		else{
			$query = "select functionPath from function where userID = $uid and functionID = $function_id";
			$result = mysqli_query($mysql,$query);
			if(!$result || !($row = mysqli_fetch_row($result))) echo "Cannot find the retrieval function";
			else
			{
				$path=$row[0];
				mysqli_free_result($result);
				if(!($file=fopen($path,"r"))) echo "";
				else
				{
					while(!feof($file)) echo fgets($file);
					fclose($file);
				}
			}
			mysqli_close($mysql);				
		}
		
	} else {
		echo "";
	}

} else {
	echo "";
}


?>
