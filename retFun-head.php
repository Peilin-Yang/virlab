<?php
include_once ("engine-head.php");
require_once ("include/mysql_connect.php");

function addFunctionGroup($userID,$theText,$groupName)
{
	global $phpExMessage;
	global $mysql;

	$originalText=$theText;
	//understand the group code
	$textArray=array();
	$paraNameArray=array();
	$paraArray=array(array());
	$i=0;
	while(preg_match('/(\w+)\s*=\s*\[([\.\d\s]+)\]/',$theText,$matches,PREG_OFFSET_CAPTURE))
	{
		$textArray[$i]=substr($theText,0,$matches[0][1]);
		$theText=substr($theText,$matches[0][1]+strlen($matches[0][0]));
		$paraNameArray[$i]=$matches[1][0];
		$theNums=$matches[2][0];
		$l=0;
		while(preg_match('/[\.\d]+/',$theNums,$matches,PREG_OFFSET_CAPTURE))
		{
			$paraArray[$i][$l++]=$matches[0][0];
			$theNums = substr($theNums,$matches[0][1]+strlen($matches[0][0]));
		}
		$i++;
	}
	//save the function group
	
	$paraCo=array();
	$paraString="";
	$valueString="";
	$totalCombineNum=1;
	if(!empty($paraNameArray))
	{
		for($i=count($paraArray)-1;$i>=0;$i--)
		{
			$paraCo[$i]=$totalCombineNum;
			$totalCombineNum*=count($paraArray[$i]);
			$paraString.=",parameter".(string)(count($paraArray)-$i);
			$valueString.=",'".$paraNameArray[$i]."'";
		}
	}
	
	$groupPath = "users/$userID/retFun/$groupName.fung";
	file_put_contents($groupPath,$originalText);
	if(count($paraArray)>5) {$phpExMessage.="The system only supports at most 5 parameters!<br/>\n";return false;}

	$query = "insert into functionGroup(userID,groupName,groupPath $paraString) values ($userID,'$groupName','$groupPath' $valueString)";
	$mysql->query($query);
	$query = "select groupID from functionGroup where userID=$userID && groupName='$groupName' && groupPath='$groupPath'";
	$resultArray = readDatabase($query);
	if(!isset($resultArray[0][0])) {$phpExMessage.="write to database failed in function group write.<br/>\n";return false;}
	$groupID=$resultArray[0][0];
	
	//save the individual functions

	if(empty($paraNameArray))
	{
		$mark=compileFunction($groupPath,$originalText);
		if($mark)
		{
			$query = "update functionGroup set groupStatus=1 where groupID=$groupID";
			$mysql->query($query);
			$functionPath = "users/$userID/retFun/$groupName.fun";
			$query = "insert into function (groupID,userID,functionPath,onlyFlag) values ($groupID,$userID,'$functionPath',1)";
			$mysql->query($query);
			file_put_contents($functionPath,$originalText);
			$phpExMessage.="Successfully add the functions!<br/>\n";
			return true;
		}
		else return false;
	}
	
	for($n=0;$n<$totalCombineNum;$n++)
	{
		$remain=$n;
		$content="";
		$funName=$groupName;
		$paraString="";
		$valueString="";
		for($i=0;$i<count($paraArray);$i++)
		{
			$value=$paraArray[$i][intval($remain/$paraCo[$i])];
			$remain=$remain % $paraCo[$i];
			$funName.="-".$paraNameArray[$i]."=$value";
			$paraString.=",functionPara".(string)($i+1);
			$valueString.=",$value";
			$content.=$textArray[$i].$paraNameArray[$i]."=".$value;
		}
		$content.=$theText;
		if($n==0) 
		{
			if(!compileFunction($groupPath,$content)) return false;
			$query = "update functionGroup set groupStatus=1 where groupID=$groupID";
			$mysql->query($query);
		}
		$functionPath = "users/$userID/retFun/$funName.fun";
		$query = "insert into function (groupID,userID,functionPath $paraString) values ($groupID,$userID,'$functionPath' $valueString)";
		//echo $query;
		$mysql->query($query);
		file_put_contents($functionPath,$content);
	}
	$phpExMessage.="Successfully add the functions!<br/>\n";
	return true;
}
		
function updateFunction($functionID,$theText) {
	$query = "select functionPath from function where functionID = $functionID ";
	$resultArray = readDatabase($query);
	$functionPath=$resultArray[0][0];
	$originalContent = file_get_contents($functionPath);
	if($originalContent != $theText) {
		$query = "select searchID from searchEngine where functionID = $functionID";
        $resultArray=readDatabase($query);
		if(isset($resultArray[0][0])) {
        	foreach($resultArray as $result) {
                clearEngineCache($result[0]);
        	}
		}
		//delete evaluations
    	$query = "delete from evaluation where functionID = $functionID";
		$mysql->query($query);
		$username = (string)$_SESSION['userID'];
		$query = "delete from user_$username where functionID = $functionID";
		$mysql->query($query);
		file_put_contents($functionPath,$theText);
		return true;
	}
	return false;
}	
        	

function updateGroup($groupID,$theText) {
	global $phpExMessage;
	global $mysql;

	clearCompareCache($groupID);
    $originalText=$theText;
    //understand the group code
    $textArray=array();
    $paraNameArray=array();
    $paraArray=array(array());
    $i=0;
	while(preg_match('/(\w+)\s*=\s*\[([\.\d\s]+)\]/',$theText,$matches,PREG_OFFSET_CAPTURE))
        {
            $textArray[$i]=substr($theText,0,$matches[0][1]);
            $theText=substr($theText,$matches[0][1]+strlen($matches[0][0]));
            $paraNameArray[$i]=$matches[1][0];
            $theNums=$matches[2][0];
            $l=0;
            while(preg_match('/[\.\d]+/',$theNums,$matches,PREG_OFFSET_CAPTURE))
            {
                    $paraArray[$i][$l++]=$matches[0][0];
                    $theNums = substr($theNums,$matches[0][1]+strlen($matches[0][0]));
            }
            $i++;
        }
        //save the old function list
	$oldFunctionList = array();
	$query = "select functionID,functionPath from function where groupID = $groupID";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0])) {
		foreach($resultArray as $result) {
			$oldFunctionList[$result[1]]=$result[0];
		}
	}
	//update the function group
	$paraCo=array();
	$paraString="";
	$totalCombineNum=1;
	if(!empty($paraNameArray)) {
		for($i=count($paraArray)-1;$i>=0;$i--) {
			$paraCo[$i]=$totalCombineNum;
			$totalCombineNum*=count($paraArray[$i]);
			$paraString.=",parameter".(string)(count($paraArray)-$i)."='".$paraNameArray[count($paraArray)-$i-1]."'";
		}
		for(;$i<=5;$i++) $paraString.=",parameter".(string)($i)."=NULL";
	}
	else for($i=1;$i<=5;$i++) $paraString.=",parameter".(string)($i)."=NULL";
	
	$query = "select groupPath from functionGroup where groupID = $groupID";
	$resultArray = readDatabase($query);
	if(!isset($resultArray[0][0])) {$phpExMessage.="update the function group $groupID fails.<br/>\n";}
	$groupPath=$resultArray[0][0];
	file_put_contents($groupPath,$originalText);
	
	if(count($paraArray)>5) {$phpExMessage.="The system only supports at most 5 parameters!<br/>\n";return false;}
	$query = "update functionGroup set groupStatus = 0 $paraString where groupID = $groupID";
	$mysql->query($query);

	//update the individual functions
	$query = "select groupName,userID from functionGroup where groupID=$groupID";
	$resultArray=readDatabase($query);
	$groupName=$resultArray[0][0];
	$userID=$resultArray[0][1];

	$mark=false;
	if($totalCombineNum<=1) {
		if(compileFunction($groupPath,$originalText)) {
			$query = "update functionGroup set groupStatus=1 where groupID=$groupID";
			$mysql->query($query);
			$functionPath = "users/$userID/retFun/$groupName.fun";
			if(isset($oldFunctionList[$functionPath])) {
				updateFunction($oldFunctionList[$functionPath],$originalText);
				unset($oldFunctionList[$functionPath]);
			}
			else {
				$query = "insert into function (groupID,userID,functionPath,onlyFlag) values ($groupID,$userID,'$functionPath',1)";
				$mysql->query($query);
				file_put_contents($functionPath,$originalText);
			}
			$mark=true;
		}
		$mark=false;
	}
	else {
		for($n=0;$n<$totalCombineNum;$n++) {
			$remain=$n;
			$content="";
			$funName=$groupName;
			$paraString="";
			$valueString="";
			for($i=0;$i<count($paraArray);$i++) {
				$value=$paraArray[$i][intval($remain/$paraCo[$i])];
				$remain=$remain % $paraCo[$i];
				$funName.="-".$paraNameArray[$i]."=$value";
	                        $paraString.=",functionPara".(string)($i+1);
	                        $valueString.=",$value";
	                        $content.=$textArray[$i].$paraNameArray[$i]."=".$value;
			}
			$content.=$theText;
			if($n==0) {
				if(!compileFunction($groupPath,$content)) {
					$mark=false;
					break;
				}
				else {
					$query = "update functionGroup set groupStatus=1 where groupID=$groupID";
	            	$mysql->query($query);
					$mark=true;
				}
			}
			$functionPath = "users/$userID/retFun/$funName.fun";
			if(isset($oldFunctionList[$functionPath])) {
				updateFunction($oldFunctionList[$functionPath],$content);
				unset($oldFunctionList[$functionPath]);
			}
			else {
				$query = "insert into function (groupID,userID,functionPath $paraString) values ($groupID,$userID,'$functionPath' $valueString)";
				$mysql->query($query);
            	file_put_contents($functionPath,$content);
			}
		}
	}
	//delete the unlinked functions
	foreach($oldFunctionList as $functionID)
	{
		deleteFunction($mysql,$mysqlEva,$functionID);
	}
	if($mark) $phpExMessage.="Successfully update the functions.<br/>\n";
	return $mark;
}	
		
function deleteFunction($functionID) {
	global $phpExMessage;
	global $mysql;

	//delete search engine
	$query = "select searchID from searchEngine where functionID = $functionID";
	$resultArray=readDatabase($query);
	if(isset($resultArray[0][0])) {
		foreach($resultArray as $result) {
			deleteEngine($result[0]);
		}
	}
	//delete evaluations
	$query = "delete from evaluation where functionID = $functionID";
	$mysql->query($query);
	$username = (string)$_SESSION['userID'];
	$query = "delete from user_$username where functionID = $functionID";
	$mysql->query($query);
	//delete the retrieval function
	$query = "select functionPath from function where functionID = $functionID";
	$resultArray=readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		exec("rm ".$resultArray[0][0]."*");
	}
	$query = "delete from function where functionID = $functionID";
	$mysql->query($query);
}

function deleteGroup($groupID) {
	global $phpExMessage;
	global $mysql;
	
	clearCompareCache($groupID);
	$query = "select groupPath from functionGroup where groupID = $groupID";
	$resultArray=readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		exec("rm ".$resultArray[0][0]."*");
	}
	$query = "delete from functionGroup where groupID = $groupID";
	$mysql->query($query);
	$query = "select functionID from function where groupID = $groupID";
	$resultArray=readDatabase($query);
	if(isset($resultArray[0][0])) {
		foreach($resultArray as $result) {
			deleteFunction($result[0]);
		}
	}
	$phpExMessage.="Successfully deleted the functions.<br/>\n";
}

function saveFunctionGroup($userID,$theText,$groupName) {
	$query = "select groupID from functionGroup where userID=$userID && groupName='$groupName'";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0])) return updateGroup($resultArray[0][0],$theText);
	else return addFunctionGroup($userID,$theText,$groupName);
}

function compileFunction($filename,$theText) {
	global $phpExMessage;

	$state=0;
	$curPos=0;
	$nextPos=0;
	$nextSymbol;
	$secMark=false;
	while($nextPos<strlen($theText))
	{
		$pos1=strpos($theText,'{',$curPos);
		$pos2=strpos($theText,'}',$curPos);
		if($pos1===false)
		{
			if($pos2===false)
			{
				$nextPos=strlen($theText);
				$nextSymbol=" ";
			}
			else
			{
				$nextPos=$pos2;
				$nextSymbol="}";
			}
		}
		else
		{
			if($pos2===false || $pos1<$pos2)
			{
				$nextPos=$pos1;
				$nextSymbol="{";
			}
			else
			{
				$nextPos=$pos2;
				$nextSymbol="}";
			}
		}
		$substr=substr($theText,$curPos,$nextPos-$curPos);
		if($state==0)
		{
			if(strpos($substr,"DF[i]")!==false || strpos($substr,"tf[i]")!==false || strpos($substr,"termPro[i]")!==false || strpos($substr,"qf[i]")!==false || strpos($substr,"TFC[i]")!==false)
			{
				$phpExMessage.= "<br/><font color='red'> DF[i],tf[i],termPro[i], qf[i], TFC[i] should be used in for(occur) or for(all) section </font><br/>\n";
				return false;
			}
			if(($thePos=strpos($substr,"if(occur)"))!==false || ($thePos=strpos($substr,"for(occur)"))!==false || ($thePos=strpos($substr,"for(all)"))!==false)
			{
				$thePos=strpos($substr,")",$thePos);
				$thePos+=1;
				if(preg_match("/^\s*$/",substr($substr,$thePos))==1)
				{
					$secMark=true;
				}
				else
				{
					$phpExMessage.= "<br/><font color='red'> for(occur) or for(all) should be followed by { ".$thePos."</font><br/>\n";
					return false;
				}
			}
		}
		else
		{
			if(($thePos=strpos($substr,"if(occur)"))!==false || ($thePos=strpos($substr,"for(occur)"))!==false || ($thePos=strpos($substr,"for(all)"))!==false)
			{
				$phpExMessage.= "<br/><font color='red'> for(occur) or for(all) should not in other section </font><br/>\n";
			}
		}
		if($nextSymbol=="{" && (($state>0) || $secMark)) $state++;
		else if($nextSymbol=="}") $state--;
		if($state<0)
		{
			$state=0;
		}
		$curPos=$nextPos+1;
		$secMark=false;
	}

	$theText = str_replace("if(occur)","if(tf[0]>0)",$theText);
	$theText = str_replace("for(occur)","if(tf[0]>0)",$theText);
	$theText = str_replace("for(all)","if(tf[0]>0)",$theText);
	$theText = str_replace("docN","1.0",$theText);
	$theText = str_replace("termN","2.0",$theText);
	$theText = str_replace("docLengthAvg","3.0",$theText);
	$theText = str_replace("MaxDF","4.0",$theText);
	$theText = str_replace("DF[i]","5.0",$theText);
	$theText = str_replace("tf[i]","tf[0]",$theText);
	$theText = str_replace("termPro[i]","6.0",$theText);
	$theText = str_replace("qf[i]","7.0",$theText);
	$theText = str_replace("TFC[i]","8.0",$theText);
	$theText = str_replace("collectionN","9.0",$theText);
	$theText = str_replace("queryLength","10.0",$theText);

	$filePath="$filename.cpp";
	$fh=fopen($filePath,'w') or die("could not write to file<br/>\n");
	fwrite($fh,"#include <math.h>\n");
	fwrite($fh,"extern \"C\" double gradeDoc(double tf[],double docLength)\n");
	fwrite($fh,"{\n\tdouble score=0;\n");
	fwrite($fh,$theText);
	fwrite($fh,"\treturn score;\n}\n");
	fclose($fh);
	
	exec("g++ -fPIC -shared $filePath -o $filePath.so 2>&1",$output);
	$mark=true;
	$phpExMessage.= "<br/><font color='red'>\n";
	foreach($output as $line)
	{
		$pos = strpos($line,": error:");
		if($pos!==false)
		{
			$hstr=substr($line,0,$pos);
			$tstr=substr($line,$pos+8);
			$lineNum=0;
			if(($pos = strrpos($hstr,":"))!==false)
			{
				$lineNum=intval(substr($hstr,$pos+1))-4;
			}
			$phpExMessage.= "Error: Line $lineNum : $tstr<br/>\n";
			$mark=false;
		}
	}
	$phpExMessage.= "</font><br/>\n";
	exec("rm $filePath*");
	return $mark;
}

function clearFunCache($functionID)
{
	$query = "select searchID from searchEngine where functionID = $functionID";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		foreach($resultArray as $result)
		{
			clearEngineCache($result[0]);
		}
	}
}

function clearCompareCache($groupID)
{
	$query = "select userID,groupName from functionGroup where groupID = $groupID";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0])) {
		$theUserID=$resultArray[0][0];
		$theName=$resultArray[0][1];
		$com = "users/$theUserID/compare/cache/*-$theName-*";
		exec("rm $com");
	}
}

?>
