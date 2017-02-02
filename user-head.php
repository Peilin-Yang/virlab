<?php
include_once ("retFun-head.php");
require_once ("conf/conf.php");
function resetPassword($userID)
{
	global $phpExMessage;
	global $mysql;

	$query = "select loginName from user where userID=$userID";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		$loginName = $resultArray[0][0];
		$password = sha1(passsalt.$loginName);
		$query = "update user set password = '$password' where userID=$userID";
		$mysql->query($query);
		$phpExMessage.="Reset the password of user $loginName successfully and the password is $loginName<br/>\n";
	}
	else $phpExMessage.="<font color='red'>Could not find the user </font><br/>\n";
}

function changePassword($userID,$oldPW,$newPW)
{
	global $phpExMessage;
	global $mysql;
	
	$password = sha1(passsalt.$oldPW);
	$query = "select loginName from user where userID=$userID && password='$password'";
	$resultArray = readDatabase($query);
	if(!isset($resultArray[0][0]))
	{
		$phpExMessage.="<font color='red'>The old password is not correct! </font><br/>\n";
		return;
	}
	$password = sha1(passsalt.$newPW);
	$query = "update user set password = '$password' where userID=$userID";
	$mysql->query($query);
	$phpExMessage.="The password has been changed successfully!<br/>\n";
}

function deleteUser($userID)
{
	global $phpExMessage;
	global $mysql;

	$query = "select loginName from user where userID=$userID and userType<=1";
	$resultArray = readDatabase($query);
	if(!isset($resultArray[0][0]))
	{
		$phpExMessage.="<font color='red'>The user could not be deleted </font><br/>\n";
		return;
	}
	else $loginName = $resultArray[0][0];

	$query = "select groupID from functionGroup where userID=$userID";
	$resultArray = readDatabase($query);
	if(isset($resultArray[0][0]))
	{
		foreach($resultArray as $result)
		{
			deleteGroup($result[0]);
		}
	}

	exec("rm -r users/$userID");
	$query = "delete from user where userID=$userID";
	$mysql->query($query);
	$phpExMessage.="Successfully Delete the user $loginName. <br/>\n";
}

