<?php session_start(); ?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>Virtual IR Lab</title>
<?php
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
require_once ("head.html");
require_once ("navigation.php");
require_once ("include/collection-head.php");
?>

<script>
var h={};
$(function() {
$("#createButton").click(function() {
  if($('#combobox-indexes').val() =='') alert("Please select an index!");
  else if($('#collectionname').val() =='') alert("Please specific a collection name!");
  else if($('#queryToUpload').val() =='') alert("Please specific a query file!");
  else if($('#qrelToUpload').val() =='') alert("Please specific a judgement file!");
  else $("#createWindow").dialog("open");
});

$("#createWindow").dialog( {
  autoOpen: false,
  resizable: false,
  height: 200,
  model: true,
  buttons: {
    "Create": function() {
    document.forms['create-collection'].indexToUse.value=h[$('#combobox-indexes').val()];
    document.forms['create-collection'].submit();
  },
  Cancel: function() {
    $(this).dialog("close");
  }
  }
});

$( "#deleteWindow" ).dialog({
  autoOpen: false,
  resizable: false,
  height: 200,
  modal: true,
  buttons: {
    "Delete": function() {
    document.forms['deleteForm'].submit();
  },
  Cancel: function() {
    document.forms['deleteForm'].deleteCollectionName.value = "";
    $(this).dialog("close");
  }
  }
});

});

function delete_click(clicked_id) {
  document.forms['deleteForm'].deleteCollectionName.value = clicked_id;
  document.getElementById("deleteWindowContent").innerHTML="Are you sure to delete collection " +clicked_id + "?";
  $("#deleteWindow").dialog("open");
}

</script>

<div id="content">
<?php
  global $mysql;
	if(isset($_SESSION['user']) && $_SESSION['admin']==255)
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
		echo "<br/>\n";
    if(isset($_POST['deleteCollectionName']) && !empty($_POST['deleteCollectionName'])) {
      $collectionName = $_POST['deleteCollectionName'];
      $query = "select collectionID from collection where collectionName = '$collectionName'";
      $resultArray = readDatabase($query);
      $collectionID = $resultArray[0][0];
      DeleteCollection($collectionID);
      $phpExMessage .= "Successfully deleted the collection $collectionName!<br/>\n";
    }
    else if(isset($_POST['indexToUse']) && isset($_POST['collectionname'])) {
      $query_file = UploadPath . basename($_FILES['queryToUpload']['name'] . ".qry");
      $qrel_file = UploadPath . basename($_FILES['qrelToUpload']['name'] . ".qrel");
      $status = 1;
      $collectionID = 0;
      if (!move_uploaded_file($_FILES['queryToUpload']['tmp_name'],$query_file) || !move_uploaded_file($_FILES['qrelToUpload']['tmp_name'],$qrel_file)) {
        $phpExMessage .= "Sorry, there was an error uploading your file.";
        $status = 0;
      }
      if ($status == 1) {
        $indexID = $_POST['indexToUse'];
        $collectionName = $_POST['collectionname'];
        $query = "insert into collection (indexID,collectionName) values ($indexID,'$collectionName')";
        if(!$mysql->query($query)) {$phpExMessage .= "Could not run the query $query!<br/>\n";$status = 0;}
        else {
          $query = "select collectionID from collection where collectionName = '$collectionName'";
          $resultArray=readDatabase($query);
          $collectionID = $resultArray[0][0];
          if($collectionID == 0) {$status = 0;}
        }
      }
      if ($status == 1) {
        if (!AddQuery($query_file,$collectionID)) $status = 0;
        else if(!AddJudge($qrel_file,$collectionID)) $status = 0;
      }
      if ($status != 1) {
        if($collectionID !=0) DeleteCollection($collectionID);
      }
      else $phpExMessage .= "Successfully set the data collection.<br/>\n";
    }
    $query = "select indexID,indexName from indexes";
    $resultArray=readDatabase($query);
    echo "Index:";
    echo "<select id='combobox-indexes'>\n";
    echo "<option value=''>Select one ...</option>\n";
    foreach($resultArray as $result) {
      $indexID=$result[0];
      $indexOption=$result[1];
      echo "<script>\n";
      echo "h['$indexOption']=$indexID;\n";
      echo "</script>\n";
      echo "<option value='$indexOption'>";
      echo $indexOption;
      echo "</option>\n";
    }
    echo "</select><br/>\n";
    echo "<form id='create-collection' action='setCollection.php' method='post' enctype='multipart/form-data'>\n";
    echo "Select a query file to upload:\n";
    echo "<input type='file' name='queryToUpload' id='queryToUpload'><br/>\n";
    echo "Select a judgement file to upload:\n";
    echo "<input type='file' name='qrelToUpload' id='qrelToUpload'><br/>\n";
    echo "<input type='hidden' name='indexToUse' id='indexToUse' value=''>\n";
    echo "Specific a collection name:\n";
    echo "<input type='text' name='collectionname' id='collectionname'><br/>\n";
    echo "</form>\n";
    echo "<button id='createButton'>Create Collection</button><br/>\n";
    echo "<br/><br/>\n";
    echo "<table id='collectionTab' class='ui-widget-content' align='center'>\n";
    echo "<thread>\n";
    echo "<tr class=ui-widget-header'>\n";
    echo "<th>Collection</th>\n";
    echo "<th>Index</th>\n";
    echo "<th>Query#</th>\n";
    echo "<th>Judgement#</th>\n";
    echo "<th>control</th>\n";
    echo "</tr>\n</thread>\n";
    echo "<tbody>\n";
    $query = "select collectionID,indexID,collectionName from collection";
    $resultArray=readDatabase($query);
    if(isset($resultArray[0][0])) 
    foreach($resultArray as $result) {
      $collectionID = $result[0];
      $indexID = $result[1];
      $collectionName = $result[2];
      $query = "select IndexName from indexes where indexID = $indexID";
      $resArray=readDatabase($query);
      $indexName = $resArray[0][0];
      $query = "select COUNT(*) from qry where collectionID = $collectionID";
      $resArray=readDatabase($query);
      $qryCount = $resArray[0][0];
      $query = "select COUNT(*) from qrel where collectionID = $collectionID";
      $resArray=readDatabase($query);
      $qrelCount = $resArray[0][0];
      echo "<tr>\n";
      echo "<td>$collectionName</td>\n";
      echo "<td>$indexName</td>\n";
      echo "<td>$qryCount</td>\n";
      echo "<td>$qrelCount</td>\n";
      echo "<td>\n";
      echo "<button id='$collectionName' onClick='delete_click(this.id)'>Delete</button>\n";
      echo "</td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n</table>\n";
    mysqli_close($mysql);
	}
	else
	{
		echo "This function is for administrator only, please <a href=\"login.php\">Login</a><br/>\n";
		header('Location: login.php');
	}
  echo $phpExMessage."<br/>\n";
	for($i=0;$i<20;$i++) echo "<br/>";
?>

<form id='deleteForm' action='setCollection.php' method='post'>
<input type='hidden' name='deleteCollectionName' value=''>
</form>

</div>

<?php include("tail.html"); ?>

<div id="createWindow" title="Create?">
<p>Are you sure to create the data collections?</p>
</div>

<div id="deleteWindow" title="Delete?">
<p id="deleteWindowContent">Are you sure to delete the collection??</p>
</div>

</body>
</html>
