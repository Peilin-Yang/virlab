<div class="content-right ten columns">

	<div id="sidebar" class="widget-area" role="complementary">
	<aside class="widget" style="float:left;">
		<h3 class="widget-title">Retrieval Function</h3>
		<ul>
			<li><a href="addFun.php">Create Function</a></li><li><a href="manageFun.php">Manage Function</a></li>	</ul>
	</aside>
	<aside class="widget" style="float:left;">
		<h3 class="widget-title">Search Engine</h3>
		<ul>
			<li><a href="addEngine.php">Create Search Engine</a></li>
			<li><a href="manageEngine.php">Manage Search Engine</a></li>
			<li><a href="compareEngines.php">Compare Search Engines</a></li>	
		</ul>
	</aside>
	<aside class="widget" style="float:left;">
		<h3 class="widget-title">LeaderBoard</h3>
		<ul>
			<li><a href="leaderBoard.php">Summary</a></li>
			<li><a href="leaderBoardPer.php">Per-collection</a></li>
		</ul>
	</aside>

	<aside class="widget" style="float:left;">
	<h3 class="widget-title">Admin</h3><ul>
	<li><a href='resetPW.php'> change Password </a></li>

<?php
	if(isset($_SESSION['user']) && isset($_SESSION['admin']) && ($_SESSION['admin']==255 || $_SESSION['admin']==127))
	{
		echo "<li><a href=\"addUser.php\">Add User</a></li><li><a href=\"manageUser.php\">Manage Users</a></li><li><a href=\"buildIndex.php\">Build Index</a></li><li><a href=\"setCollection.php\">Set Collection</a></li>\n";
	}
?>
	</ul></aside>
	<aside class="widget">
    <h3 class="widget-title">About</h3>
	<ul>
        <li><a href="/about.php">People</a></li>   </ul>
    </aside>
</div>
