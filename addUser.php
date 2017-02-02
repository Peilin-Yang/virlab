<?php 
  require_once ("include/superHead.php"); 
  require_once ("conf/conf.php");
  $phpExMessage="";
  require_once ("include/mysql_connect.php");
  include_once ("retFun-head.php");
  include("head.html");
  include_once ("navigation.php");
?>
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>add User | IR virtual Lab</title>

<?php //session_start(); 
 ?>

<script>
  function approve_click(clicked_id)
  {
    document.forms['decisionForm'].approve.value = clicked_id;
    $("#approveWindow").dialog("open");
  }

  function decline_click(clicked_id)
  {
    document.forms['decisionForm'].decline.value = clicked_id;
    $("#declineWindow").dialog("open");
  }

  $(function() {
    $( "#confirmWindow" ).dialog({
      autoOpen: false,
      resizable: false,
      height:200,
      modal: true,
      buttons: {
        "Add the user": function() {
          document.forms["addForm"].submit();
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });

  $( "#submitButton" ).click(function() {
    $( "#confirmWindow" ).dialog( "open" );
  });

  $( "#approveWindow" ).dialog({
  	autoOpen: false,
  	resizable: false,
  	height: 200,
  	buttons: {
    	  "Approve": function() {
    		document.forms['decisionForm'].submit();
    	},
    	  Cancel: function() {
    		$(this).dialog( "close" );
    	 }
  	}
  });

  $( "#declineWindow" ).dialog({
    autoOpen: false,
    resizable: false,
    height: 200,
    buttons: {
      "Decline": function() {
            document.forms['decisionForm'].submit();
    },
    Cancel: function() {
            $(this).dialog( "close" );
    }
    }
  });

  });
</script>

<?php
function initialUser($userID)
{
	exec("mkdir -p users/$userID");
  exec("mkdir -p users/$userID/retFun");
  exec("mkdir -p users/$userID/engine");
}
?>

<div id="content">
<?php
  global $mysql;
	if(isset($_SESSION['user']) && isset($_SESSION['admin']) && ($_SESSION['admin']==255 || $_SESSION['admin']==127))
	{
	//add user table
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
    echo "<form id='addForm' action=\"addUser.php\" method='post'><br/>\n";
		echo "Username: *<input type='text' name='addUsername' maxLength='30'/><br/>\n";
		echo "Password: *<input type='text' name='addPassword' /><br/>\n";
		echo "First Name: <input type='text' name='firstName' maxLength='50' /><br/>\n";
		echo "Last Name: <input type='text' name='lastName' maxLength='50' /><br/>\n";
		echo "Affiliation: *<input type='text' name='affiliation' maxLength='128' /><br/>\n";
		echo "Email: *<input type='text' name='email' maxLength='50' /><br/>\n";
		echo "</form>\n";
		echo "<button id=\"submitButton\">Submit</button><br/>\n";
	
	//back end operation for the adding users
		if(isset($_POST['approve']) && !empty($_POST['approve']))
		{
			echo "approve ".$_POST['approve']."!<br/>\n";
			//$query = "update user set usertype=1 where userID =". $_POST['approve'];
			$query = "update user set usertype=1 where userID =". $_POST['approve'];
			$mysql->query($query);
			initialUser($_POST['approve']);
		}
		else if(isset($_POST['decline']) && !empty($_POST['decline']))
		{
			$decline=$_POST['decline'];
			$query = "delete from user where userID=$decline";
			$mysql->query($query);
			echo "decline request from user $decline!<br/>\n";
		}

		if(isset($_POST['addUsername']) && !empty($_POST['addUsername']) &&!empty($_POST['addPassword']) && !empty($_POST['affiliation']) && !empty($_POST['email']))
		{
			$loginName = $_POST['addUsername'];
			$password = sha1('wh'.$_POST['addPassword']);
			$firstName = $_POST['firstName'];
			$lastName = $_POST['lastName'];
			$affiliation = $_POST['affiliation'];
			$email = $_POST['email'];
			$userGroup = MyUserGroupID;
			$query = "select userID from user where loginName = '$loginName'";
			$resultArray = readDatabase($query);
			if(isset($resultArray[0][0]))
			{
				$phpExMessage.= "The userName is already exists!<br/>\n";
			}
			else
			{
			//$query="insert into user(loginName,password,usertype,firstName,lastName,affiliation,email) values ('$loginName','$password',1,'$firstName','$lastName','$affiliation','$email')";
			$query="insert into user(loginName,password,usertype,firstName,lastName,affiliation,email,userGroup) values ('$loginName','$password',1,'$firstName','$lastName','$affiliation','$email',$userGroup)";
			$mysql->query($query);
			$query = "select userID from user where loginName = '$loginName'";
			$resultArray = readDatabase($query);
			if(isset($resultArray[0][0]))
			{
				initialUser($resultArray[0][0]);
				$phpExMessage.= "Added user $loginName<br/>\n";
			}
			else $phpExMessage.= "Add user $loginName failed <br/>\n";
			}
		}

    //Display approve user table
    $query = "select userID,loginName,firstName,lastName,affiliation,email,userGroup from user where usertype=0";
    $resultArray = readDatabase($query);
    echo "<table id='tabUser' class='ui-widget ui-widget-content'>\n";
    echo "<thread>\n";
    echo "<tr class='ui-widget-header'>\n";
    echo "<th>loginName</th><th>firstName</th><th>lastName</th><th>affiliation</th><th>email</th><th>control</th>\n";
    echo "</tr></thread>\n";
    echo "<tbody>\n";
    if(isset($resultArray[0][0])) foreach($resultArray as $i=>$row)
    {
			if($resultArray[$i][6]==MyUserGroupID)
			{
        $userID=$resultArray[$i][0];
        $loginName=$resultArray[$i][1];
        $firstName=$resultArray[$i][2];
        $lastName=$resultArray[$i][3];
        $affiliation=$resultArray[$i][4];
        $email=$resultArray[$i][5];
        echo "<tr>\n";
        echo "<td> $loginName </td><td> $firstName </td><td> $lastName </td><td> $affiliation </td><td> $email </td>\n";
        echo "<td>\n";
        echo "<button id='$userID' onClick='approve_click(this.id)'>APPROVE</button>\n";
        echo "<button id='$userID' onClick='decline_click(this.id)'>DECLINE</button>\n";
        echo "</td>\n";
        echo "<tr>\n";
			}
    }
    echo "</tbody>\n</table>\n";
		
		//echo $phpExMessage;
	} else {
		echo "This function is only for administrator, please <a href=\"login.php\">Login</a><br/>\n";
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>

<form id='decisionForm' action='addUser.php' method='post'>
<input type='hidden' name='approve' value=''>
<input type='hidden' name='decline' value=''>
</form>

</div>

<?php include("tail.html"); ?>

<div id="confirmWindow" title="Add the user?">
<p>Are you sure to add the user?</p>
</div>

<div id="approveWindow" title="approve the user?">
<p>Are you sure to approve the user's request?</p>
</div>

<div id="declineWindow" title="decline the user?">
<p>Are you sure to decline the user's request?</p>
</div>

</body>
</html>
