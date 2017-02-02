<?php

function getAP($judgeTab,$rankTab)
{
	if(count($judgeTab)==0) return 0;
	$AP=0;
	$rnum=0;
	//echo count($rankTab)." ".count($judgeTab)."<br/>\n";
	for($i=0;$i<count($rankTab);$i++)
	{
		$doc=$rankTab[$i];
		if(isset($judgeTab["$doc"]) && $judgeTab["$doc"]>=1) $AP+=(++$rnum)/($i+1);
	}
	return $AP/count($judgeTab);
}

function getP30($judgeTab,$rankTab)
{
	$n=30;
	if(count($rankTab)<$n) $n=count($rankTab);
	$P=0;
	for($i=0;$i<$n;$i++)
	{
		$doc=$rankTab[$i];
		if(isset($judgeTab["$doc"]) && $judgeTab["$doc"]>=1) $P++;
	}
	return $P/30;
}

function evaTopic($indexPath,$retPath,$collection,$topic,$theQry)
{
	$judgeTab=array();
	$rankTab=array();
	$query="select docName,score from qrel_$collection where topic='$topic'";
	$resultArray = readDatabase($query);
	//if(!$result) {echo "Could not get the judgement $topic in $collection.<br/>\n";}
	if(isset($resultArray[0][0]))
	{
	foreach($resultArray as $result)
	{
		$doc=$result[0];
		$score=$result[1];
		$judgeTab["$doc"]=$score;
	}
	}
	$com="source/retrieval-flexible-web $indexPath $retPath $theQry";
	//echo "$com<br/>\n";
	if(!$file=popen($com,"r")) echo "Could not execute the command $com<br/>\n";
	$rank=0;
	while($line=fgets($file))
	{
		$item=explode("\t",$line);
		//echo "$line<br/>\n";
		$doc=$item[0];
		//echo "$doc<br/>\n";
		$rankTab[$rank++]=$doc;
	}
	pclose($file);
	$res[0]=getAP($judgeTab,$rankTab);
	//echo $res[0]."<br/>\n";
	$res[1]=getP30($judgeTab,$rankTab);
	return $res;
}


function evaCollection($functionID,$collectionID)
{
	global $mysql;
	$MAPTab=array();
	$P30Tab=array();

	$query="select indexID,collectionName from collection where collectionID = $collectionID";
	$resultArray = readDatabase($query);
	$indexID = $resultArray[0][0];
	$collectionName = $resultArray[0][1];
	$query="select indexPath from indexes where indexID = $indexID";
	$resultArray = readDatabase($query);
	$indexPath = $resultArray[0][0];
	$query="select groupID,functionPath from function where functionID = $functionID";
	$resultArray = readDatabase($query);
	$groupID = $resultArray[0][0];
	$functionPath = $resultArray[0][1];
	$query="select userID from functionGroup where groupID = $groupID";
	$resultArray = readDatabase($query);
	$userID = $resultArray[0][0];
	$username = (string)$userID;
	 

	$query="show tables like 'user_$username'";
	$result=$mysql->query($query);
	if(!$result->fetch_row())
	{
		$query="create table user_$username (collectionID int unsigned,functionID int unsigned,topic char(10),MAP double,P30 double)";
		if(!$mysql->query($query)) {"could not create the table of the evaluation<br/>\n";return;}
	}
	$result->free();

	$query="select topic,query from qry_$collectionName";
	$resultArray = readDatabase($query);
	foreach($resultArray as $result)
	{
		$topic=$result[0];
		$theQry=$result[1];

		$query="select MAP,P30 from user_$username where collectionID=$collectionID && functionID=$functionID && topic='$topic'";
		$resultArray2 = readDatabase($query);
		if(isset($resultArray2[0][0]))
		{
			$MAPTab["$topic"]=$resultArray2[0][0];
			$P30Tab["$topic"]=$resultArray2[0][1];
		}
		else
		{
			$res=evaTopic($indexPath,$functionPath,$collectionName,$topic,$theQry);
			$MAPs = $res[0];
			$P30s = $res[1];
			$MAPTab["$topic"]=$res[0];
			$P30Tab["$topic"]=$res[1];
			$query="insert into user_$username (collectionID,functionID,topic,MAP,P30) values ($collectionID,$functionID,'$topic',$MAPs,$P30s)";
			if(!$mysql->query($query)) {"Insert topic result fails<br/>\n";}
		}
	}
	$MAP=0;
	$P30=0;
	foreach($MAPTab as $topic => $value) $MAP+=$value;
	foreach($P30Tab as $topic => $value) $P30+=$value;
	$MAP/=count($MAPTab);
	$P30/=count($P30Tab);
	$MAP=round($MAP,4);
	$P30=round($P30,4);
	$query="insert into evaluation (collectionID,userID,functionID,MAP,P30) values ($collectionID,$userID,$functionID,$MAP,$P30)";
	if(!$mysql->query($query)) {"Could not insert the results <br/>\n";return;}	
}

?> 
