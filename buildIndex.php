<?php 
set_time_limit(0); 
ob_implicit_flush(1); 

require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
require_once ("head.html");
require_once ("navigation.php");
include_once ("include/collection-head.php");
?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>Virtual IR Lab</title>
<script>

$(function() {
$("#buildButton").click(function() {
  if(document.forms['buildIndex'].indexname.value == '') alert("Please type an index name");
  else if(document.forms['buildIndex'].textfile.value == '') alert("Please upload a data file");
  else {
    $("#confirmWindow").dialog("open");
  }
});

$("#cancelButton").click(function() {
  document.forms['buildIndex'].cancel.value = 'cancel';
  document.forms['buildIndex'].submit();
});

$( "#confirmWindow").dialog({
  autoOpen: false,
  resizable: false,
  height: 200,
  modal: true,
  buttons: {
    "Create": function() {
    document.forms['buildIndex'].submit();
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
    document.forms['deleteForm'].deleteIndexName.value = "";
    $(this).dialog("close");
  }
  }
});

});

function delete_click(clicked_id) {
  document.forms['deleteForm'].deleteIndexName.value = clicked_id;
  document.getElementById("deleteWindowContent").innerHTML="Are you sure to delete index " +clicked_id + "?";
  $("#deleteWindow").dialog("open");
}

</script>

<?php
function BuildIndex() {
  echo "<div style=\"margin:50px auto;  padding: 8px; border: 1px solid gray; background: #EAEAEA; width: 500px\">\n";
  echo "<div id=\"build_status\">&nbsp;</div>\n";
  echo "</div>\n";
  $cmd = BuildIndexProgram ." ". IndexPath .$_POST['indexname']." ".UploadPath.$_POST['textfile'];
  //$cmd = "perl tt1.pl";
  echo "Begin Build the index of ".$_POST['indexname']."<br/>\n";
  flush();
  $handle = popen("$cmd 2>&1 &","r");
  flush();
  $current_num = "";
  $current_pos = 0;
  while(!feof($handle)) {
    usleep(100000);
    $read = fgets($handle, 1024);
    $num = trim($read," \n\r");
    //$num = trim($num," ");
    if($num != "") {
      $current_num = $num;
    }
    $process = "";
    for($i=0;$i<10;$i++) {
      if($current_pos == $i) $process.='>';
      else $process.='-';
    }
    echo "<script language=\"JavaScript\">\n";
    echo "document.getElementById(\"build_status\").innerHTML = \"$current_num  $process\";\n";
    echo "</script>\n";
    $current_pos = ($current_pos+1)%10;
    flush();
  }
  pclose($handle);
}

function DeleteCorpus() {
  $target_file = UploadPath . $_POST['textfile'];
  exec("rm $target_file");
}

?>

<div id="content">
<?php
  global $mysql;
	if(isset($_SESSION['user']) && $_SESSION['admin']==255)
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
		echo "<br/>\n";
    $textfile = '';
    $status = 0;
    if(isset($_POST['deleteIndexName']) && !empty($_POST['deleteIndexName'])) {
      if(DeleteIndex($_POST['deleteIndexName'])) {
        $phpExMessage .= "Successfully Delete the Index ".$_POST['deleteIndexName']."<br/>\n";
      } else {
        $phpExMessage .= "The index cannot be deleted<br/>\n";
      }
    }
    if(isset($_POST['textfile']) && !empty($_POST['textfile'])) {
      if ($_POST['cancel'] != 'cancel') {
        $query = "select indexID from indexes where indexName = '".$_POST['indexname']."'";
        $resultArray = readDatabase($query);
        if(isset($resultArray[0][0])) {
          $phpExMessage .= "The index ".$_POST['indexname']." is already exists!<br/>\n";
        } else {
          BuildIndex();
          $indexpath = IndexPath .$_POST['indexname'];
          $query = "insert into indexes(indexName,indexPath) values ('".$_POST['indexname']."','$indexpath')";
          $result = $mysql->query($query);
          if($result) {
            $status = 2;
            echo "Index ".$_POST['indexname']." is built successfully!<br/>\n";
          } else $phpExMessage.="database failed <br/>\n";
        }
      }
      DeleteCorpus();
    }
    if(isset($_POST['upload'])) {
      $target_file = UploadPath . basename($_FILES["fileToUpload"]["name"]);
      $status = 1;
      if ($_FILES['fileToUpload']['size'] > 2048*1000*1000) {
        $phpExMessage .= "Sorry, your file should be smaller than 2G";
        $status = 0;
     }
    }
    if ($status == 1) {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $textfile = basename( $_FILES["fileToUpload"]["name"]);
        $phpExMessage .= "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        $phpExMessage .= "Sorry, there was an error uploading your file.";
        $status = 0;
      }
    }
    if ($status == 0) {
      echo "<form action='buildIndex.php' method='post' enctype='multipart/form-data'>\n";
      echo "Select a text file to upload:\n";
      echo "<input type='file' name='fileToUpload' id='fileToUpload'>\n";
      echo "<input type='submit' value='Upload File' name='upload'>\n";
      echo "</form>\n";
      echo "<br/><br/>\n";
      echo "<table id='indexTab' class='ui-widget-content' align='center'>\n";
      echo "<thread>\n";
      echo "<tr class=ui-widget-header'>\n";
      echo "<th>Index</th>\n";
      echo "<th>Size(MB)</th>\n";
      echo "<th>Control</th>\n";
      echo "</tr>\n</thread>\n";
      echo "<tbody>\n";
      $query = "select indexID,indexName,indexPath from indexes";
      $resultArray=readDatabase($query);
      if(isset($resultArray[0][0]))
      foreach($resultArray as $result) {
        $indexID = $result[0];
        $indexName = $result[1];
        $indexPath = $result[2];
        $indexSize = GetIndexSize($indexPath);
        echo "<tr>\n";
        echo "<td>$indexName</td>\n";
        echo "<td>$indexSize</td>\n";
        echo "<td><button id='$indexName' onClick='delete_click(this.id)'>Delete</button></td>\n";
        echo "</tr>\n";
      }
      echo "</tbody>\n</table>\n";
    } else if ($status == 1) {
      echo "<form id='buildIndex' action='buildIndex.php' method='post'>\n";
      echo "Index Name: <input type='text' name='indexname'><br/>\n";
      echo "Text File: <input type='text' name='textfile' value='$textfile' readonly><br/>\n";
      echo "<input type='hidden' name='cancel' value=''>\n";
      echo "</form>\n";
      echo "<button id='buildButton'>Build Index</button>\n";
      echo "<button id='cancelButton'>Cancel</button><br/>\n";
    }
	}
	else
	{
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
		header('Location: login.php');
	}
  echo $phpExMessage."<br/>\n";
	for($i=0;$i<20;$i++) echo "<br/>";
?>

<form id='deleteForm' action='buildIndex.php' method='post'>
<input type='hidden' name='deleteIndexName' value=''>
</form>

</div>

<?php include("tail.html"); ?>

<div id="confirmWindow" title="Add?">
<p>Are you sure to build the index?</p>
</div>

<div id="deleteWindow" title="Delete?">
<p id="deleteWindowContent">Are you sure to delete the index??</p>
</div>

</body>
</html>
