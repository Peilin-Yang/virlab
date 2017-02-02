<?php 
  include("include/superHead.php");
  include("include/mysql_connect.php");
?>

<?php

function getAllUsers() {
    $sql_user = mysqli_connect('p:'.MysqlHost,MysqlUser,MysqlPassword,'users2',MysqlPort);
    $all_users = array();

    $query="select * from user where userType >= 1 and userGroup = " . MyUserGroupID;
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
            $all_users[] = array($userID, $loginName, $firstName, $lastName);
        }
    }

    mysqli_close($sql_user);

    return $all_users;
}

?>

<!DOCTYPE html>
<html>

    <head>
        <?php include("include/leaderBoardHeader.php"); ?>
    </head>

    <body onload="sh_highlightDocument();">
    <?php //session_start(); 
    if(!isset($_SESSION['user'])){
        echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
        header('Location: login.php');
    }
    ?>

    <div class="container">
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
                <?php
                    $allUsers = getAllUsers();
                    foreach ($allUsers as $user) {
                        echo "<tr>";
                        $idx = 0;
                        foreach ($user as $v) {
                            if ($idx == 0) {
                                echo "<td><a href='uid.php?uid=$v'>$v</a></td>";
                            } else {
                                echo "<td>$v</td>";
                            }
                            $idx++;
                        }
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    </body>
</html>
