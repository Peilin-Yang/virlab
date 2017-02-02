<?php include("include/superHead.php"); ?>

<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
	<title>About | IR virtual Lab</title>
<?php
  include("head.html");
  include("navigation.php"); 
?>


<div id="content">
<?php
	if(isset($_SESSION['user']))
	{
		echo "<p text-align:right>";
		echo "Welcome,".$_SESSION['user'];
		echo ",<a href=\"logout.php\">logout</a></p><br/>\n";
		
		echo "
        <h3>Project Lead</h3>

        <table border=\"0\" cellpadding=\"5\" cellspacing=\"5\">
        <tr>
        <td align=\"center\"><img src=\"css/pic-hui.png\" alt=\"photo of Hui\" /></td>
        <td><b><a href=\"http://www.eecis.udel.edu/~hfang\">Hui Fang</a></b><br/>
        Assistant Professor<br/>
        University of Delaware</td>
        </tr>

        <tr>
        <td align=\"center\"><img src=\"css/pic-zhai.png\" alt=\"photo of Chengxiang\"/></td>
        <td><b><a href=\"http://www.cs.uiuc.edu/~czhai/\">ChengXiang Zhai</a></b><br/>Associate Professor<br/>University of Illinois at Urbana Champaign</td>
        </tr>

        </table>

        <h3>Active Contributors</h3>

        <table border=\"0\" cellpadding=\"5\" cellspacing=\"5\">

        <tr>
        <td align=\"center\"><img src=\"css/pic-hao.png\" alt=\"photo of Hao\" /></td>
        <td><b><a href=\"#\">Hao Wu</a></b><br/>Ph.D Student<br/>University of Delaware<br/></td>
        </tr>

        <tr>
        <td align=\"center\"><img src=\"css/pic-peilin.png\" alt=\"photo of Peilin\"/></td>
        <td><b><a href=\"http://www.eecis.udel.edu/~ypeilin\">Peilin Yang</a></b><br/>Ph.D Student<br/>University of Delaware <br/></td>
        </tr>

        </table>

        <h3>Contact Us</h3>
        <a href=\"mailto:IRVirtualLab@gmail.com\" target=\"_top\">IRVirtualLab@gmail.com</a>

        ";
	}
	else
	{
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
		header('Location: login.php');
	}
	for($i=0;$i<20;$i++) echo "<br/>";
?>

</div>

<?php include("tail.html"); ?>


</body>
</html>
