<?php 
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
require_once ("head.html");
require_once ("navigation.php");
include_once ("showFun.php");
include_once ("include/evaluation.php");
?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>Evaluation | IR virtual Lab</title>
<script>
function eva_click(clicked_id)
{
	alert("The evaluation may take several minutes! Thanks for your patient!");
	var strs = clicked_id.split(",");
	document.forms['evaForm'].collectionID.value = strs[0];
	document.forms['evaForm'].functionID.value = strs[1];
	document.forms['evaForm'].submit();
}
</script>

<?php
	
?>

<div id="content">
<?php
	if(isset($_SESSION['user']))
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
		
		//the title

		$groupID=$_POST['groupID'];

		$userID=$_SESSION['userID'];
		
		$query = "select groupName from functionGroup where groupID=$groupID";
		$resultArray = readDatabase($query);
		$groupName = $resultArray[0][0];

		echo "<h1> $groupName </h1><br/>\n";
		showGroup($groupID);

		//the Collections
		$query = "select collectionID,collectionName from collection";
		$resultArray = readDatabase($query);
		$evaList=array();
		foreach($resultArray as $result) {
			$evaList[$result[0]]=$result[1];
		}
		
		if(isset($_POST['functionID']) && isset($_POST['collectionID']))
		{
			evaCollection($_POST['functionID'],$_POST['collectionID']);
		}

		$query = "select parameter1,parameter2,parameter3,parameter4,parameter5 from functionGroup where groupID = $groupID";
		$resultArray = readDatabase($query);
		$paraArray = array();
		$paraString = "";
		$paraN = 0;
		if(!empty($resultArray[0][0])) {$paraArray[$paraN]=$resultArray[0][0];$paraN++;$paraString.=",functionPara1";}
		if(!empty($resultArray[0][1])) {$paraArray[$paraN]=$resultArray[0][1];$paraN++;$paraString.=",functionPara2";}
		if(!empty($resultArray[0][2])) {$paraArray[$paraN]=$resultArray[0][2];$paraN++;$paraString.=",functionPara3";}
		if(!empty($resultArray[0][3])) {$paraArray[$paraN]=$resultArray[0][3];$paraN++;$paraString.=",functionPara4";}
		if(!empty($resultArray[0][4])) {$paraArray[$paraN]=$resultArray[0][4];$paraN++;$paraString.=",functionPara5";}

		foreach($evaList as $collectionID => $collectionName)
		{
			echo "<h1>$collectionName</h1><br/>\n";
			echo "<table class='ui-widget ui-widget-content'>\n";
			echo "<thread>\n";
			echo "<tr class='ui-widget-header'>\n";
			//echo "<th>Evaluation Collection</th>\n";
			//for($i=0;$i<$paraN;$i++)
			for($i=$paraN-1;$i>=0;$i--)
			{
				echo "<th>".$paraArray[$i]."</th>\n";
			}
			echo "<th>MAP</th>\n";
			echo "<th>P30</th>\n";
			echo "</tr>\n</thread>\n";
			echo "<tbody>\n";
			$query = "select functionID$paraString from function where groupID = $groupID";
			$resultArray = readDatabase($query);
			foreach($resultArray as $result)
			{
				echo "<tr>\n";
				$functionID = $result[0];
				for($i=0;$i<$paraN;$i++)
				{
					echo "<td>".$result[$i+1]."</td>\n";
				}
				$query = "select MAP,P30 from evaluation where functionID = $functionID && collectionID = $collectionID";
				$resultArray2 = readDatabase($query);
				if(isset($resultArray2[0][0]))
				{
					echo "<td>".$resultArray2[0][0]."</td>\n";
					echo "<td>".$resultArray2[0][1]."</td>\n";
				}
				else
				{
					$theKeyID = $collectionID. "," . $functionID;
					echo "<td><button id='$theKeyID' onClick='eva_click(this.id)'>Evaluation</button></td>\n";
				}
				echo "</tr>\n";
			}
			echo "</tbody></table>\n";
			echo "<br/><br/>\n";
		}
		echo "<form id='evaForm' action='evaFun.php' method='post'>\n";
		echo "<input type='hidden' name='collectionID' value=''>\n";
		echo "<input type='hidden' name='functionID' value=''>\n";
		echo "<input type='hidden' name='groupID' value='$groupID'>\n";
		echo "</form>\n";
		echo "<font color='red'>$phpExMessage</font>\n";
	}
	else
	{
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>

</div>

<?php include("tail.html"); ?>


</body>
</html>
