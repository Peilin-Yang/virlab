<?php 
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
include_once ("include/leaderBoardHeader.php");
?>
<!DOCTYPE html>
<html>
    <head>
    </head>

    <body onload="sh_highlightDocument();">
    <?php //session_start(); 
    if(!isset($_SESSION['user'])){
        echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
        header('Location: login.php');
    }
    ?>

    <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <a class="navbar-brand" href="home.php">IR Virtual Lab</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="navbar-collapse pull-right">
        <ul class="nav navbar-nav pull-right">
          <li><a href="addFun.php">Retrieval Function</a></li>
          <li><a href="addEngine.php">Search Engine</a></li>
          <li class="active"><a href="#">LeaderBoard</a></li>
          <li><a href="/about.php">About</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size:16px;">Welcome, <?php echo $_SESSION['user']; ?><b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="logout.php">Log Out</a></li>
            </ul>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
      </div>
    </nav>
    

    <div class="container" style="min-height:100%;margin-right:auto;margin-left:auto;margin-top:80px;">
    <div class="col-lg-2 well" style="padding:3px;">
    <ul class="nav nav-pills nav-stacked">
        <li style="padding:0px;"><a href="leaderBoard.php">Summary</a></li>
        <li class="active" style="padding:0px;"><a href="#">Per-collection</a></li>
    </ul>
    </div>
    <div class="col-lg-10">

    <?php 
    global $mysql;
    $username=$_SESSION['user'];
    $uid=$_SESSION['userID'];

    //echo "uid uid uid:".$uid."<br>";
    //echo "username username username:".$username."<br>";

    $LeaderBoard=array();
    $query_index="select collectionID,collectionName from collection order by collectionName";
    $result_index = $mysql->query($query_index);
    if(!$result_index) echo "Could not get index list.<br/>\n";
    else
    {
        while($row_index = $result_index->fetch_row())
        {
            $function_array = array();
            $leaderBoard_cnt = 0;
            $index=$row_index[0];
            $collectionName=$row_index[1];
            if (!array_key_exists($collectionName, $LeaderBoard)){
                $LeaderBoard[$collectionName] = array();
            }
            //echo "<em>$index</em><br>";
            $query_eval="select * from evaluation where collectionID=$index order by map desc, p30 desc";
            //echo "$query_eval<br>";
            $result_eval = $mysql->query($query_eval);
            if(!$result_eval) {
                echo "Could not get evaluation results list.<br/>\n";
            } else {
                while($row_eval = $result_eval->fetch_row()) {
                    $evaluationID=$row_eval[0];
                    $uid=$row_eval[1];
                    $collectionID=$row_eval[2];
                    $functionID=$row_eval[3];
                    $MAP=$row_eval[4];
                    $P30=$row_eval[5];

                    if($uid == 0){
                        // super user, do not have user name.
                        continue;
                    }

                    if (in_array($functionID, $function_array)) {
                        continue;
                    }

                    //echo $evaluationID."===>".$uid."===>".$collectionID."===>".$functionID."===>".$MAP."===>".$P30."<br>";

                    $query_user="select userID,loginName,userType,userGroup from user where userID=$uid";
                    $results_user=$mysql->query($query_user);
                    $row_user=$results_user->fetch_row();
                    $userID=$row_user[0];
                    $user=$row_user[1];
                    $userType=$row_user[2];
                    $userGroup=$row_user[3];
                    //echo $user;
                    $results_user->free();
    
                    if ($userType >= 1 && $userGroup == MyUserGroupID) {
                        $query_func="select * from function where userID=$uid and functionID=$functionID and onlyFlag=1";
                        $results_func=$mysql->query($query_func);
                        //var_dump($results_func);
                        if ($results_func->num_rows != 0) {
                            //echo $evaluationID."===>".$uid."===>".$collectionID."===>".$functionID."===>".$MAP."===>".$P30."<br>";
                            $row_func=$results_func->fetch_row();
                            //var_dump($row_func);
                            $funtion_path=$row_func[4];
                            //echo "onlyFlag:".$row_func[3]."<br>";
                            $func_path_split=explode('/', $funtion_path); 
                            $ret_func=substr($func_path_split[sizeof($func_path_split)-1], 0, sizeof($func_path_split[sizeof($func_path_split)-1])-5);

                            //echo $user."--->".$ret_func."--->".$collectionName."--->".$MAP."--->".$P30."<br>";
                            //echo $evaluationID."===>".$uid."===>".$collectionID."===>".$functionID."===>".$MAP."===>".$P30."<br>";
                            $LeaderBoard[$collectionName][] = array("userID" => $userID, "user" => $user, "funcID" => $functionID, "ret_func" => $ret_func, "collectionID" => $index, "collection" => $collectionName, "MAP" => $MAP, "P30" => $P30); 
                            $leaderBoard_cnt++;
                            $function_array[] = $functionID;
                            if ($leaderBoard_cnt >= 10) {
                                break;
                            }
                        }
                        $results_func->free();
                    }
                }
            }
            $result_eval->free();
        }
        $result_index->free(); 
    }

    //var_dump($LeaderBoard);


    echo "<h3 style=\"text-align:center;\">Top 10 retrieval functions for each collection</h3>";
    echo "<br>";
    echo "<ul class=\"nav nav-tabs\">";
    $outer_i=0;
    foreach($LeaderBoard as $index => $ranks){
        if ($outer_i == 0){
            echo "<li class=\"active\"><a href=\"#$index\" data-toggle=\"tab\">$index</a></li>";
        } else {
            echo "<li><a href=\"#$index\" data-toggle=\"tab\">$index</a></li>";
        }
        $outer_i++;
    }
    echo "</ul>";
    echo "<div class=\"tab-content\">";

    $outer_i=0;
    foreach($LeaderBoard as $index => $ranks){
        if ($outer_i == 0){
            echo "<div class=\"tab-pane active\" id=\"$index\">";
        } else {
            echo "<div class=\"tab-pane\" id=\"$index\">";
        }

        echo "<h3>Top effective functions for collection $index</h3>";
        echo "<hr>";
        
        echo "<table class=\"table table-bordered table-hover\" style=\"text-align:center;\">";
        echo "<thead>
            <tr>
            <th>Rank</th>
            <th>Retrieval Function</th>
            <th>User</th>
            <th>MAP</th>
            <th>P@30</th>
            </tr>
            </thead>
            <tbody>";

        $keys = array_keys($ranks);
        if (sizeof($keys) > 0){
            for ($i=0;$i<sizeof($keys);$i++){
                if ($i >= 10){
                    break;
                }
                $user = $ranks[$keys[$i]]["user"];
                $userID = $ranks[$keys[$i]]["userID"];
                $funcID = $ranks[$keys[$i]]["funcID"];
                $func = $ranks[$keys[$i]]["ret_func"];
                $collectionID = $ranks[$keys[$i]]["collectionID"];
                echo "<tr>";
                echo "<td>".($i+1)."</td>";
                if ($userID == $_SESSION['userID']){
                    echo "<td>$func+</td>";
                } else {
                    echo "<td>$func</td>";
                }
                echo "<td>$user</td>";
                echo "<td>".$ranks[$keys[$i]]["MAP"]."</td>";
                echo "<td>".$ranks[$keys[$i]]["P30"]."</td>";   
                echo "</tr>";             
            }
        } else {
            echo "<tr><td colspan=\"5\">There is NO evaluation for this collection yet.</td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
        
        echo "</div>";
        $outer_i++;
    }

    echo "</div>";

    ?>
    
    <div>* Rankings are first based on MAP and then P@30.</div>
    <div>+ means it is your function.</div>

    </div>
    </body>
</html>
