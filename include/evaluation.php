<?php

include_once (__DIR__."/../conf/conf.php");
require_once (__DIR__."/../include/mysql_connect.php");

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

function evaTopic($indexPath,$funcPath,$collectionID,$topic,$theQry)
{
	$judgeTab=array();
	$rankTab=array();
	$query="select docName,score from qrel where topic='$topic' and collectionID='$collectionID'";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		foreach($resultArray as $result)
		{
			$doc=$result[0];
			$score=$result[1];
			$judgeTab["$doc"]=$score;
		}
	}
	
	$com=RetrievalProgramDirect." $indexPath $funcPath $theQry";
	if(!$file=popen($com,"r")) echo "An error occurred executing the command $com.".str_system_support_err."<br/>\n";
	$rank=0;
	while($line=fgets($file))
	{
		$item=explode("\t",$line);
		//echo "$line<br/>\n";
		$doc=$item[1];
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

	$query="select indexID from collection where collectionID = $collectionID";
	$resultArray = readDatabase($query);
	$indexID = $resultArray[0][0];
	$query="select indexPath from indexes where indexID = $indexID";
	$resultArray = readDatabase($query);
	$indexPath = $resultArray[0][0];
	$query="select userID,functionPath from function where functionID = $functionID";
	$resultArray = readDatabase($query);
	$userID = $resultArray[0][0];
	$functionPath = $resultArray[0][1];
	
    $query="select topic,query from qry where collectionID=$collectionID";
	$resultArray = readDatabase($query);
	foreach($resultArray as $result) {
		$topic=$result[0];
		$theQry=$result[1];
    	$query="select MAP,P30 from eval where collectionID=$collectionID && functionID=$functionID && topic='$topic'";
		$resultArray2 = readDatabase($query);
		if(isset($resultArray2[0][0]))
		{
			$MAPTab["$topic"]=$resultArray2[0][0];
			$P30Tab["$topic"]=$resultArray2[0][1];
		}
		else
		{
			$res=evaTopic($indexPath,$functionPath,$collectionID,$topic,$theQry);
			$MAPs = $res[0];
			$P30s = $res[1];
			$MAPTab["$topic"]=$res[0];
			$P30Tab["$topic"]=$res[1];
			$query="insert into eval (collectionID,functionID,topic,MAP,P30) values ($collectionID,$functionID,'$topic',$MAPs,$P30s)";
			if(!$mysql->query($query)) { echo "An error occurred when inserting evaluation results into the database.".str_system_support_err."<br/>\n";}
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
	if(!$mysql->query($query)) {echo "An error occurred when inserting evaluation results into the database.".str_system_support_err."<br/>\n";return;}	
}

?> 
