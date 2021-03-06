<?php
require_once (__DIR__."/../include/superHead.php"); 
require_once (__DIR__."/../conf/conf.php");
$phpExMessage="";
require_once (__DIR__."/../include/mysql_connect.php");
$myRetProgram="$myBasePath/source/retrieval-f-qry";
$myCache="cache";
$mySnippet="snippet";
$mySnippetProgram="$myBasePath/source/getSnippet";
$myShowDocPhp="$myBasePath/source/showDoc.php";
if($myIndexPath[0]!='/') $myIndexPath = $myBasePath."/".$myIndexPath;
?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="author" content="Hao Wu">
<meta name="description" content="Information Retrieval Virtual Lab">

<meta name="viewport" content="width=device-width">

<link rel="shortcut icon" href="http://www.udel.edu/modules/icons/images/ud.ico">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="jquery/jquery-linedtextarea.js"></script>
<script src="http://jqueryui.com/jquery-wp-content/themes/jquery/js/plugins.js"></script>
<script src="http://jqueryui.com/jquery-wp-content/themes/jquery/js/main.js"></script>

<link rel="stylesheet" href="http://jqueryui.com/jquery-wp-content/themes/jquery/css/base.css?v=1">
<link rel="stylesheet" href="http://jqueryui.com/jquery-wp-content/themes/jqueryui.com/style.css">
<link href="jquery/jquery-linedtextarea.css" type="text/css" rel="stylesheet" />

<div id="container">
<div id="logo-events" class="constrain clearfix">
<?php echo "<a href=\"$myBasePath/../home.php\"><img src=\"$myBasePath/../static/img/logo.png\"></a>\n"; ?>
<nav id="main" class="constrain clearfix"> </nav>
<div id="content-wrapper" class="clearfix row">

<script>

$(function() {

$( "#evalButton" ).click(function() {
	$("#evalWindow").dialog("open");
});

$( "#evalWindow" ).dialog({
	autoOpen: false,
	resizable: true,
	height: 500,
	width: 1000,
	modal: true,
	buttons: {
	  close: function() {
		$(this).dialog( "close" );
	}
	}
});

$( "#accordion" ).accordion({ heightStyle: "content" });

});

</script>

<form action="search.php" method="get">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" name="qry" size="100" value="<?php if(isset($_GET["qry"])) echo $_GET["qry"];?>" />
<input type="submit" value="search"/>
</form>
<button id='evalButton'>Official Query</button><br/>

<div id="menu" style="width:150px;float:left;">
.....

</div>

<div id="content" style="width:800px;float:left;">
<?php
	global $mysql;
	$qry="";
	if(isset($_GET["qry"])) $qry = escapeshellcmd($_GET["qry"]);
	if ($qry == "") {
	  //echo "You don't input any content<br />\n";
	} else
	if($myPrivacyLevel==1 && (!isset($_SESSION['userID']) || $_SESSION['userID']!=$myUserID)) {
	  echo "This search engine is for creator only!!<br/>\n";
	} else if($myPrivacyLevel==2 && !isset($_SESSION['userID'])) {
	  echo "This search engine is for registered users only! Please <a href=\"$myBasePath/../home.php\">login</a><br/>\n";
	} else {
	$timer = microtime(true);

	$qryTerms=str_replace(" ","_",$qry);
	//Generate results
	$cacheFile="$myCache/" . $qryTerms;
	if(!file_exists($cacheFile)) {
		$command = "$myRetProgram $myIndexPath $myFunctionPath " . $cacheFile . " " . $qry;
		//echo $command;
		exec($command);
	}

	//Read the evaluation if possible
	$qrelTab=array();
	$relN=0;
	if(isset($_GET["collection"]) && isset($_GET["topic"])) {
	    $collectionName = $_GET["collection"];
	    $query = "select collectionID from collection where collectionName = '$collectionName'";
	    $resultArray = readDatabase($query);
	    $collectionID = $resultArray[0][0];
		//$qrelFile="qrel_".$_GET["collection"];
		$query = "select docName,score from qrel where collectionID = $collectionID and topic='".$_GET["topic"]."'";
		$resultArray = readDatabase($query);
		foreach($resultArray as $result) {
			$qrelTab[$result[0]]=$result[1];
			if($result[1]>0) $relN++;	
		}
	}	
	//Read results from cache
	if(isset($_GET["start"])) $beginR=$_GET["start"];
	else $beginR=0;
	if($beginR=="" || $beginR<0) $beginR=0;
	if($beginR>=1000) $beginR=990;
	$beginR=(int)($beginR/10)*10;
	$endR=$beginR+10;

	$i=0;
	$docs;
	$docString="";
	$docIDs;
	$scores;
	$snippets;
	$file=fopen($cacheFile,"r") or exit("Unable to open cache file!");
	$retDocN=substr(fgets($file),0,-1);

	if($relN>0) {
		$MAP=0;
		$rnum=0;
		$P30=0;
		while(!feof($file)) {
			$i++;
			$line=fgets($file);
			if(empty($line)) break;
			$split=explode("\t",$line);
			if(isset($qrelTab[$split[1]]) && $qrelTab[$split[1]]>0)
			{
				$MAP+=($rnum++)/$i;
				if($i<=30) $P30++;
			}
			if($i>$beginR && $i <= $endR)
			{
				$docs[$i-$beginR-1]=$split[1];
				$docIDs[$i-$beginR-1]=$split[2];
				$docString.="_".$split[2];
				$scores[$i-$beginR-1]=$split[3];
			}
		}
		echo "<font color='red'>MAP=".$MAP/$relN." &nbsp&nbsp";
		echo "P30=".$P30/30.0."</font><br/>\n";
	}
	else {
		while(!feof($file) && ($i++) < $endR) {
			$line=fgets($file);
			if($i>$beginR)
			{
				$split=explode("\t",$line);
				$docs[$i-$beginR-1]=$split[1];
				$docIDs[$i-$beginR-1]=$split[2];
				$docString.="_".$split[2];
				$scores[$i-$beginR-1]=$split[3];
			}
		}
	}
	fclose($file);
	
	//Generate snippet

	$snippetFile="$mySnippet/" . $qryTerms . "-" . $beginR;
	if(!file_exists($snippetFile)) {
		$command = "$mySnippetProgram $myIndexPath ".$snippetFile." ".$docString." ".$qryTerms;
		//echo $command;
		exec($command);
	}
	
	//read snippet
	$file=fopen($snippetFile,"r") or exit("No results found<br/>\n");
	$i=0;
	while(!feof($file)) {
		$snippets[$i++]=substr(strchr(fgets($file),"\t"),1);
	}
	fclose($file);

	$timer = microtime(true)-$timer;

	//output results
	$pageNum=$beginR/10+1;
	echo "<font size=2 color=\"grey\">page ".$pageNum." of ".$retDocN. " results (".$timer." s) </font><br>\n";

	for($i=0;$i<sizeof($docs);$i++) {
		echo "<p><font size=5>";
		echo "<a href=\"$myShowDocPhp?qry=".$_GET["qry"]."&docID=".$docIDs[$i]."&doc=".$docs[$i]."&index=$myIndexID"."\">".$docs[$i]."</a></font><br>\n";
		echo "<font size=2 color=\"green\">".$scores[$i]." </font>";
		if(isset($qrelTab[$docs[$i]])) {
			if($qrelTab[$docs[$i]]>0)
				echo "&nbsp&nbsp&nbsp<font size=2 color=\"red\">Relevant(".$qrelTab[$docs[$i]].")</font>";
			else echo "&nbsp&nbsp&nbsp<font size=2 color=\"red\">Non-relevant(".$qrelTab[$docs[$i]].")</font>";
		}
		echo "<br>\n";
		echo $snippets[$i]."<br>\n";
		echo "</p>\n";
	}

	//add link to other pages
	echo "<p>";
	if($beginR>0) {
		$startL=(int)(($beginR-10)/10)*10;
		if(isset($_GET["collection"]) && isset($_GET["topic"]))
			echo "<a href='search.php?qry=".$_GET["qry"]."&start=$startL&collection=".$_GET["collection"]."&topic=".$_GET["topic"]."'>Previous</a>";
		else
			echo "<a href=\"search.php?qry=".$_GET["qry"]."&start=".$startL."\">Previous</a>";
	}
	echo " ";

	$linkB=$beginR/10-4;
	$linkE=$beginR/10+4;
	if($linkB<0) $linkE+=0-$linkB;
	if($linkE>($retDocN-1)/10) {$linkB-=$linkE-(int)(($retDocN-1)/10);$linkE=(int)(($retDocN-1)/10);}
	if($linkB<0) $linkB=0;
	for($i=$linkB+1;$i<=$linkE+1;$i++) {
		if($i==$beginR/10+1)
			echo $i." ";
		else {
			$startL=($i-1)*10;
			if(isset($_GET["collection"]) && isset($_GET["topic"]))
				echo "<a href='search.php?qry=".$_GET["qry"]."&start=$startL&collection=".$_GET["collection"]."&topic=".$_GET["topic"]."'>$i </a>";
			else
				echo "<a href=\"search.php?qry=".$_GET["qry"]."&start=".$startL."\">$i </a> ";
		}
	}
	if($beginR+10<$retDocN) {
		$startL=(int)(($beginR+10)/10)*10;
		if(isset($_GET["collection"]) && isset($_GET["topic"]))
			echo "<a href='search.php?qry=".$_GET["qry"]."&start=$startL&collection=".$_GET["collection"]."&topic=".$_GET["topic"]."'>Next</a>";
		else
			echo "<a href=\"search.php?qry=".$_GET["qry"]."&start=".$startL."\">Next</a><br>\n";
	}
	echo "</p>\n";
}

?>
<div id="evalWindow" title="Official Evaluation">
<div id="accordion">
<?php
	$query="select collectionID,collectionName from collection where indexID = $myIndexID";
	$resultArray=readDatabase($query);
	foreach($resultArray as $result) {
    	$collectionID=$result[0];
		$collectionName=$result[1];
		echo "<h3>$collectionName</h3>\n";
		//$theQuery = "qry_$collectionName";
		echo "<div>\n";
		$query="select topic,query from qry where collectionID = $collectionID";

		$resultArray2 = readDatabase($query);
		foreach($resultArray2 as $result2) {
			$topic=$result2[0];
			$qry=$result2[1];
			echo "<a href='search.php?qry=$qry&collection=$collectionName&topic=$topic'>$topic:$qry</a><br/>\n";
		}
		echo "</div>\n";
		
	}
?>
</div>
</div>

</div></div></div></div>
