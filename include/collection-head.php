<?php
function AddQuery($query_file,$collectionID) {
  global $phpExMessage;
  global $mysql;
  if(!($file=fopen($query_file,"r"))) {$phpExMessage .= "Could not open the query file!<br/>\n";return false;}
  while(!feof($file)) {
    $line=fgets($file);
    if(empty($line)) continue;
    $items=preg_split("/[:\n]+/",$line);
    $topic=$items[0];
    $qry=$items[1];
    $query = "insert into qry (collectionID,topic,query) values ($collectionID,'$topic','$qry')";
    if(!$mysql->query($query)) {$phpExMessage .= "Could not insert the query $query into database<br/>\n";return false;}
  }
  fclose($file);
  unlink($file);
  return true;
}

function AddJudge($qrel_file,$collectionID) {
  global $phpExMessage;
  global $mysql;
  if(!($file=fopen($qrel_file,"r"))) {$phpExMessage .= "Could not open the judgement file!<br/>\n";return false;}
  while(!feof($file)) {
    $line=fgets($file);
    if(empty($line)) continue;
    $items=preg_split("/\s+/",$line);
    $topic=$items[0];
    $docName=$items[1];
    $score=$items[2];
    $query = "insert into qrel (collectionID,topic,docName,score) values ($collectionID,'$topic','$docName',$score)";
    if(!$mysql->query($query)) {$phpExMessage .= "Could not inset the query $query into database<br/>\n";return false;}
  }
  fclose($file);
  unlink($file);
  return true;
}

function DeleteCollection($collectionID) {
  global $phpExMessage;
  global $mysql;
  $query = "delete from qry where collectionID = $collectionID";
  $mysql->query($query);
  $query = "delete from qrel where collectionID = $collectionID";
  $mysql->query($query);
  $query = "delete from collection where collectionID = $collectionID";
  $mysql->query($query);
}

function DeleteIndex($indexName) {
  global $phpExMessage;
  global $mysql;
  $query = "select indexID,indexPath from indexes where indexName = '$indexName'";
  $resultArray=readDatabase($query);
  if(isset($resultArray[0][0])) {
    $indexID = $resultArray[0][0];
    $indexPath = $resultArray[0][1];
    $query = "select collectionID from collection where indexID = $indexID";
    $resultArray=readDatabase($query);
    if(isset($resultArray[0][0]))
    foreach($resultArray as $result) {
      DeleteCollection($result[0]);
    }
    $query = "delete from indexes where indexID = $indexID";
    $mysql->query($query);
    $com = "rm -r $indexPath";
    exec($com);
    return true;
  } else return false;
}

function GetIndexSize($indexPath) {
  global $phpExMessage;
  $com = "du -sm $indexPath | cut -f1";
  $handle = popen("$com 2>&1","r");
  $read = fgets($handle,1024);
  $num = trim($read," \n\r");
  return $num;
}

?>
