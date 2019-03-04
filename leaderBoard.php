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

    <body>
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
          <li><a href="about.php">About</a></li>
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
        <li class="active" style="padding:0px;"><a href="#">Summary</a></li>
        <li style="padding:0px;"><a href="leaderBoardPer.php">Per-collection</a></li>
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
            $index=$row_index[0];
            $collectionName=$row_index[1];
            if (!array_key_exists($collectionName, $LeaderBoard)){
                $LeaderBoard[$collectionName] = array();
            }
            //echo "<em>$collectionName</em><br>";
            $query_eval="select * from evaluation where collectionID=$index order by map desc, p30 desc";
            $result_eval = $mysql->query($query_eval);
            if(!$result_eval) {
                echo "Could not get evaluation results list.<br/>\n";
            } else {
                while($row_eval = $result_eval->fetch_row()){
                    $evaluationID=$row_eval[0];
                    $uid=$row_eval[1];
                    $collectionID=$row_eval[2];
                    $functionID=$row_eval[3];
                    $MAP=$row_eval[4];
                    $P30=$row_eval[5];

                    if($uid == 1){
                        // super user, do not have user name.
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

                    //echo $user."--->".$ret_func."--->".$collectionName."--->".$MAP."--->".$P30."<br>";
                    if ($userType >= 1 && $userGroup == MyUserGroupID){
                        $query_func="select * from function where userID=$uid and functionID=$functionID and onlyFlag=1";
                        $results_func=$mysql->query($query_func);
                        if ($results_func->num_rows != 0) {
                            $row_func=$results_func->fetch_row();
                            //var_dump($row_func);
                            $funtion_path=$row_func[4];
                            //echo "onlyFlag:".$row_func[3]."<br>";
                            $func_path_split=explode('/', $funtion_path); 
                            $ret_func=substr($func_path_split[sizeof($func_path_split)-1], 0, sizeof($func_path_split[sizeof($func_path_split)-1])-5);

                            //echo $user."--->".$ret_func."--->".$collectionName."--->".$MAP."--->".$P30."<br>";
                            //echo $evaluationID."===>".$uid."===>".$collectionID."===>".$functionID."===>".$MAP."===>".$P30."<br>";
                            $LeaderBoard[$collectionName][] = array("userID" => $userID, "user" => $user, "funcID" => $functionID, "ret_func" => $ret_func, "collectionID" => $index, "collection" => $collectionName, "MAP" => $MAP, "P30" => $P30);
                            $results_func->free();
                            break;
                        }
                    }  
                }
            }
            $result_eval->free(); 
        }
        $result_index->free(); 
    }

    //$code = "/* *** asdfsdf ****/ \r sfdjlfgjflg \n/* *\r *\n sdfsdfsdfdsf*\n\n**\n*/";
    //preg_match_all("/\/\*([^*]|[\r\n]|(\*+([^*\/]|[\r\n])))*\*+\//", $code, $matches);
    //echo var_dump($matches);

    //echo var_dump($LeaderBoard);
    echo "<h3>The most effective function for each collection</h3>";
    echo "<hr>";
    echo "<table id=\"bestOnes\" class=\"table table-bordered table-hover\" style=\"text-align:center;\">";
    echo "<thead>
        <tr>
        <th>Collection</th>
        <th>Best Retrieval Function</th>
        <th>User</th>
        <th>MAP</th>
        <th>P@30</th>
        </tr>
        </thead>
        <tbody>";
        foreach($LeaderBoard as $index => $ranks){
            //echo "$index<br>";
            $keys = array_keys($ranks);
            if (sizeof($keys) > 0){
                $user = $ranks[$keys[0]]["user"];
                $userID = $ranks[$keys[0]]["userID"];
                $funcID = $ranks[$keys[0]]["funcID"];
                $func = $ranks[$keys[0]]["ret_func"];
                $collectionID = $ranks[$keys[0]]["collectionID"];
                echo "<tr>";
                echo "<td>$index</td>";
                if ($userID == $_SESSION['userID']){
                    echo "<td>$func+</td>";
                } else {
                    echo "<td>$func</td>";
                }
                echo "<td>$user</td>";
                echo "<td>".$ranks[$keys[0]]["MAP"]."</td>";
                echo "<td>".$ranks[$keys[0]]["P30"]."</td>";
                /*
                echo "<td>
                <a href=\"./compareBest.php?fid=$funcID&collection=$index\" target=\"_blank\">Per-query</a>&nbsp&nbsp
                <a href=\"./compareBestCollection.php?fid=$funcID&baseline=$func&collectionID=$collectionID&collection=$index\" target=\"_blank\">Overview</a>&nbsp&nbsp";

                if (array_key_exists($index, $already_evaluated)){
                    $diff = array_diff_key($this_user_funcs, $already_evaluated[$index]);
                    if (sizeof($diff) > 0){
                        echo "<span href=\"#show_modal\" id=\"$index"."<===>";
                        foreach($diff as $f => $f_path){
                            echo $f."<===>";
                        }
                        echo "\" class=\"badge show_incomplete\" style=\"cursor:pointer;\" data-toggle=\"modal\" >".sizeof($diff)."/".sizeof($this_user_funcs)."</span>";
                    }
                } else {
                    echo "<span href=\"#show_modal\" id=\"$index"."<===>";
                        foreach($this_user_funcs as $f => $f_path){
                            echo $f."<===>";
                        }
                        echo "\" class=\"badge show_incomplete\" style=\"cursor:pointer;\" data-toggle=\"modal\"> ".sizeof($this_user_funcs)."/".sizeof($this_user_funcs)."</span>";      
                }
                
                echo "</td>";
                */
                echo "</tr>";  
            } else {
                echo "<tr>";
                echo "<td>$index</td>";
                echo "<td colspan=\"5\">There is still NO evaluation for this collection yet.</td>";
                echo "</tr>"; 
            }
        }

        echo "</tbody>";
        echo "</table>";
    ?>

    <div>* Rankings are first based on MAP and then P@30.</div>
    <div>+ means it is your function.</div>

    </div>
    </body>
</html>
