<?php 
  include("include/superHead.php");
  include("include/mysql_connect.php");
?>
<!DOCTYPE html>
<html>

    <head>
        <?php include("include/leaderBoardHeader.php"); ?>
                <script>
        $(document).ready(function(){
            $( ".show_func" ).click(function() {
                var splits = this.id.split('<===>');
                $.get("./getFunc.php", { uid: splits[0], fid: splits[1] })
                    .done(function(data) {
                        //alert(data);
                        $(".modal-title").html("Show Function");
                        $("#modal_content").html("<p><strong>User:"+splits[2]+"&nbsp&nbspFunction:"+splits[3]+"</strong></p><pre class=\"sh_cpp\">"+data+"</pre>");
                        sh_highlightDocument();
                });
            });

            $( ".show_incomplete" ).click(function() {
                var splits = this.id.split('<===>');
                var show = "*Please note that the following functions have not been evaluated for collection "
                +splits[0]+". Try to evaluate them (clicking on functions) and see whether your can be the best! <br><br>";
                show += "<ul>";
                for (var i = 1; i < splits.length; i++){
                    if (splits[i].length > 0){
                        var _id = splits[i];
                        var func_name = _id.split(':::')[0];
                        var groupID = _id.split(':::')[1];
                        show += "<li><form action=\"evaFun.php\" method=\"post\">" + "<input type=\"hidden\" name=\"groupID\" value=\"" + groupID + "\" />" + "<button type=\"submit\" class=\"btn btn-default btn-sm\">"+func_name+"</button>" + "</form></li>";//"<li><a id=\""+_id+"\" style=\"cursor:pointer;\" onClick=\"eval_func(this.id);\">"+splits[i]+"</a></li>";
                    }
                }
                show += "</ul>";
                $(".modal-title").html("Incomplete Evaluation");
                $("#modal_content").html(show);
            });

            function eval_func(_id) {
                alert("hehe");
                var url = 'evaFun.php';
                var form = $('<form action="' + url + '" method="post">' + '<input type="text" name="groupID" value="' + _id + '" />' + '</form>');
                $('body').append(form);
                $(form).submit();
            }

        });

        </script> 
    </head>

    <body onload="sh_highlightDocument();">
    <?php //session_start(); 
    if(!isset($_SESSION['user'])){
        echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
        header('Location: login.php');
    }
    ?>

    <?php

        function getThisUser() {
            $sql_user = mysqli_connect('p:'.MysqlHost,MysqlUser,MysqlPassword,'users2',MysqlPort);

            $query="select * from user where userID = " . $_GET['uid'];
            $result = mysqli_query($sql_user,$query);
            if(!$result) echo "Could not get index list.<br/>\n";
            else
            {
                while($row = mysqli_fetch_row($result))
                {
                    $userID = $row[0];
                    $loginName = $row[1];
                    $firstName = $row[4];
                    $lastName = $row[5];
                    $this_user = array($userID, $loginName, $firstName, $lastName);
                }
            }

            mysqli_close($sql_user);

            return $this_user;
        }

    ?> 


    <div class="container">
        <a href="allUsers.php">Users List</a>
    <div>
    <table class="table table-bordered table-hover" style="text-align:center;">
        <thead>
            <tr> 
                <th>userID</th>
                <th>loginID</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                    $this_user = getThisUser();
                    foreach ($this_user as $v) {
                        echo "<td>$v</td>";
                    }
                ?>
            </tr>
        </tbody>
    </table>

    <?php 
    $username=$_SESSION['user'];
    $uid=$_SESSION['userID'];
    $getuid=$_GET['uid'];

    //echo "uid uid uid:".$uid."<br>";
    //echo "username username username:".$username."<br>";

    $sql_collection = mysqli_connect('p:'.MysqlHost,MysqlUser,MysqlPassword,'collections2',MysqlPort);
    if(!$sql_collection) echo "Could not connect to databases.<br/>\n";
    $sql_eval = mysqli_connect('p:'.MysqlHost,MysqlUser,MysqlPassword,'evaluations2',MysqlPort);
    $sql_user = mysqli_connect('p:'.MysqlHost,MysqlUser,MysqlPassword,'users2',MysqlPort);
    if(!$sql_user) echo "Could not connect to databases.<br/>\n";

    $LeaderBoard=array();
    $query_index="select collectionID,collectionName from collection order by collectionName";
    $result_index = mysqli_query($sql_collection,$query_index);
    if(!$result_index) echo "Could not get index list.<br/>\n";
    else
    {
        while($row_index = mysqli_fetch_row($result_index))
        {
            $function_array = array();
            $leaderBoard_cnt = 0;
            $index=$row_index[0];
            $collectionName=$row_index[1];
            if (!array_key_exists($collectionName, $LeaderBoard)){
                $LeaderBoard[$collectionName] = array();
            }
            //echo "<em>$index</em><br>";
            $query_eval="select * from evaluation where collectionID=$index and userID=$getuid order by map desc, p30 desc";
            //echo "$query_eval<br>";
            $result_eval = mysqli_query($sql_eval,$query_eval);
            if(!$result_eval) {
                echo "Could not get evaluation results list.<br/>\n";
            } else {
                while($row_eval = mysqli_fetch_row($result_eval)){
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
                    $results_user=mysqli_query($sql_user,$query_user);
                    $row_user=mysqli_fetch_row($results_user);
                    $userID=$row_user[0];
                    $user=$row_user[1];
                    $userType=$row_user[2];
                    $userGroup=$row_user[3];
                    //echo $user;
                    mysqli_free_result($results_user);
    
                    if ($userType >= 1 && $userGroup == MyUserGroupID){
                        $query_func="select * from function where userID=$uid and functionID=$functionID and onlyFlag=1";
                        $results_func=mysqli_query($sql_user,$query_func);
                        //var_dump($results_func);
                        if (mysqli_num_rows($results_func) != 0) {
                            //echo $evaluationID."===>".$uid."===>".$collectionID."===>".$functionID."===>".$MAP."===>".$P30."<br>";
                            $row_func=mysqli_fetch_row($results_func);
                            //var_dump($row_func);
                            $funtion_path=$row_func[4];
                            //echo "onlyFlag:".$row_func[3]."<br>";
                            $func_path_split=explode('/', $funtion_path); 
                            $ret_func=substr($func_path_split[sizeof($func_path_split)-1], 0, sizeof($func_path_split[sizeof($func_path_split)-1])-5);

                            //echo $user."--->".$ret_func."--->".$collectionName."--->".$MAP."--->".$P30."<br>";
                            //echo $evaluationID."===>".$uid."===>".$collectionID."===>".$functionID."===>".$MAP."===>".$P30."<br>";
                            $LeaderBoard[$collectionName][] = array("userID" => $userID, "user" => $user, "funcID" => $functionID, "fPath" => $funtion_path, "ret_func" => $ret_func, "collectionID" => $index, "collection" => $collectionName, "MAP" => $MAP, "P30" => $P30); 
                            $leaderBoard_cnt++;
                            $function_array[] = $functionID;
                            if ($leaderBoard_cnt >= 100) {
                                break;
                            }
                        }
                        mysqli_free_result($results_func);
                    }
                }
            }
            mysqli_free_result($result_eval);
        }
        mysqli_free_result($result_index); 
    }
    mysqli_close($sql_user);
    mysqli_close($sql_collection);
    mysqli_close($sql_eval);

    //var_dump($LeaderBoard);

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
        
        echo "<table class=\"table table-bordered table-hover\" style=\"text-align:center;\">";
        echo "<thead>
            <tr>
            <th>Rank</th>
            <th>Retrieval Function</th>
            <th>Function Path</th>
            <th>MAP</th>
            <th>P@30</th>
            </tr>
            </thead>
            <tbody>";

        $keys = array_keys($ranks);
        if (sizeof($keys) > 0){
            for ($i=0;$i<sizeof($keys);$i++){
                $user = $ranks[$keys[$i]]["user"];
                $userID = $ranks[$keys[$i]]["userID"];
                $funcID = $ranks[$keys[$i]]["funcID"];
                $funPath = $ranks[$keys[$i]]["fPath"];
                $func = $ranks[$keys[$i]]["ret_func"];
                $collectionID = $ranks[$keys[$i]]["collectionID"];
                echo "<tr>";
                echo "<td>".($i+1)."</td>";
                echo "<td><button data-toggle=\"modal\" href=\"#show_modal\" class=\"btn btn-info btn-sm show_func\" id=\"$userID<===>$funcID<===>$user<===>$func\">$func</button></td>";
                echo "<td>$funPath</td>";
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

    </div>

    <!-- Modal -->
    <div class="modal fade" id="show_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Show Function</h4>
        </div>
        <div class="modal-body">
            <div class="accordion" id="modal_content">
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    </div>

    </body>
</html>
