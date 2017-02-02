<?php 
require_once ("include/superHead.php"); 
require_once ("conf/conf.php");
$phpExMessage="";
require_once ("include/mysql_connect.php");
require_once ("include/compareHeader.php");
?>
<!DOCTYPE html>
<html>

	<head>
	    <script>
	    $(document).ready(function(){
			$("#offical_query_tip").hover(function(){
				$("#offical_query_tip").tooltip('show');
				},function(){
				$("#offical_query_tip").tooltip('hide');
			});

			// get your select element and listen for a change event on it
			$('#select_collection').change(function() {
				// set the window's location property
				var q = document.getElementById("qry_input").value;
				var func1 = $('#select_func1').val();
				var func2 = $('#select_func2').val();
				var c = $('#select_collection').val();
				window.location = "compareEngines.php?qry="+q+"&first_func="+func1+"&second_func="+func2+"&collection="+c;
			});

			$(".official_q").click(function(){
				document.getElementById("qry_input").value = this.getAttribute("value");
				$('#offical_query').modal('hide');
			});
	            $("#submit_form_btn").click(function(){
	                $('#start').val(0);
	                $('#this_form').submit();
			});
	    });
	    </script>
	    <script>
	    function pagination(_id){
	    	//alert(_id);
	    	var start = _id.split('_')[1];
	    	var q = "<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['qry']:$_POST['qry']; ?>";

			$('#qry_input').val(q);
			$('#select_func1').val("<?php echo $_POST['first_func']; ?>");
			$('#select_func2').val("<?php echo $_POST['second_func']; ?>");
			$('#start').val(start);

			$('#this_form').submit();
	    }
	    </script>
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
	      <li class="active"><a href="#">Search Engine</a></li>
	      <li><a href="leaderBoard.php">LeaderBoard</a></li>
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
		<li style="padding:0px;"><a href="addEngine.php">Create Search Engine</a></li>
		<li style="padding:0px;"><a href="manageEngine.php">Manage Search Engine</a></li>
		<li class="active" style="padding:0px;"><a href="#">Compare Search Engine</a></li>
	</ul>
	</div>
	<div class="col-lg-10">
	<div>
	<form id="this_form" action="./compareEngines.php" method="post" class="form-inline">
	<div class="row">
	<div class="col-lg-11">
	<div class="row">
		<div class="col-lg-12">
	    	<input id="qry_input" name="qry" class="form-control" type="search" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST') {echo $_POST["qry"];} if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["qry"])) {echo $_GET["qry"];}?>" placeholder="Type your query here">
	  	</div>
	</div>

	<p/>

	<div class="row">
		<!-- Compare button -->
		<div class="col-lg-3" style="padding-right:0px;">
			<button type="button" data-toggle="modal" href="#offical_query" class="btn btn-success btn-block">Select a TREC query</button>
		</div>
		
		<!-- Compare button -->
		<div class="col-lg-9" style="padding-left:0px;">
			<button type="button" id="offical_query_tip" class="btn glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="You will be able to see evaluation results when selecting TREC queries."></button>
		
			<select name="first_func" id="select_func1" class="form-control form_select" style="margin-left:30px;">
				<option value="">First Function</option>
				<?php
				global $mysql;
				if(isset($_SESSION['user']))
				{
					$username=$_SESSION['user'];
					$uid=$_SESSION['userID'];

					$query= "select * from function where userID = $uid and onlyFlag = 1" ;
					$result = $mysql->query($query);
					if(!$result) echo "Could not run query.<br/>\n";
					else
					{
						//echo "<ul class=\"dropdown-menu\">\n";
						while($row = $result->fetch_row())
						{
							$funtion_path=$row[4];
							$func_path_split=explode('/', $funtion_path); 
							$retFunOption=substr($func_path_split[sizeof($func_path_split)-1], 0, sizeof($func_path_split[sizeof($func_path_split)-1])-5);
							if (isset($_GET['first_func']) || isset($_POST['first_func'])){
								//echo $retFunOption;
								$request_func=$_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['first_func']:$_POST['first_func'];
								if ($request_func===$retFunOption){
									echo "<option value=\"$retFunOption\" selected=\"selected\">$retFunOption</option>";
								} else {
									echo "<option value=\"$retFunOption\">$retFunOption</option>";
								}
							} else {
								echo "<option value=\"$retFunOption\">$retFunOption</option>";
							}
						}
						$result->free();
						//echo "</ul>\n";
					}
				}
				else
				{
					echo "This toolkit is only for register users, please <a href=\"../../../login.php\">Login</a><br/>\n";
					//header('Location: login.php');
				}
				?>
			</select>
		    <select name="second_func" id="select_func2" class="form-control form_select" style="margin-left:10px;">
		    	<option value="">Second Function</option>
				<?php
				global $mysql;
				if(isset($_SESSION['user']))
				{
					$username=$_SESSION['user'];
					$uid=$_SESSION['userID'];

					$query= "select * from function where userID = $uid and onlyFlag = 1" ;
					$result = $mysql->query($query);
					if(!$result) echo "Could not run query.<br/>\n";
					else
					{
						//echo "<ul class=\"dropdown-menu\">\n";
						while($row = $result->fetch_row())
						{
							$funtion_path=$row[4];
							$func_path_split=explode('/', $funtion_path); 
							$retFunOption=substr($func_path_split[sizeof($func_path_split)-1], 0, sizeof($func_path_split[sizeof($func_path_split)-1])-5);
							if (isset($_GET['second_func']) || isset($_POST['second_func'])){
								//echo $retFunOption;
								$request_func=$_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['second_func']:$_POST['second_func'];
								if ($request_func===$retFunOption){
									echo "<option value=\"$retFunOption\" selected=\"selected\">$retFunOption</option>";
								} else {
									echo "<option value=\"$retFunOption\">$retFunOption</option>";
								}
							} else {
								echo "<option value=\"$retFunOption\">$retFunOption</option>";
							}
						}
						$result->free();
						//echo "</ul>\n";
					}
				}
				else
				{
					echo "This toolkit is only for register users, please <a href=\"../../../login.php\">Login</a><br/>\n";
					//header('Location: login.php');
				}
				?>
		    </select>
		    <select name="collection" id="select_collection" class="form-control form_select" style="margin-left:10px;">
		    	<option value="">Document Collection</option>
				<?php
				global $mysql;
				if(isset($_SESSION['user']))
				{
					$query="select collectionID,collectionName from collection";
					$result = $mysql->query($query);
					if(!$result) echo "Could not run query.<br/>\n";
					else
					{
						while($row = $result->fetch_row())
						{
							$theOption=$row[1];
							if (isset($_GET['collection']) || isset($_POST['collection'])){
								//echo $retFunOption;
								$request_collection=$_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection'];
								if ($request_collection===$theOption){
									echo "<option value=\"$theOption\" selected=\"selected\">$theOption</option>";
								} else {
									echo "<option value=\"$theOption\">$theOption</option>";
								}
							} else {
								echo "<option value=\"$theOption\">$theOption</option>";
							}
						}
						$result->free();
						//echo "</ul>\n";	
					}
				}
				else
				{
					echo "This toolkit is only for register users, please <a href=\"../../../login.php\">Login</a><br/>\n";
					//header('Location: login.php');
				}
				?>
		    </select>
		    
		</div>
	</div>
	</div>
	<div class="col-lg-1">
		<a href="#" id="submit_form_btn" class="btn btn-info btn-compare">Compare</a>
	</div>
	<div class="col-lg-1" style="padding-left:0px;">
		<input class="form-control" name="start" id="start" type="hidden" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'POST'?$_POST['start']:""; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'POST'?$_POST['start']:""; ?>">
	</div>
	</div>
	</form>

	<?php 
	global $mysql;
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$form_valid = true;
		$qry_valid = true;
		$first_func_valid = true;
		$second_func_valid = true;
		$collection_valid = true;
		if(!(strlen($_POST["qry"]) > 0)) {
			$form_valid = false;
			$qry_valid = false;
		}
		if(!(strlen($_POST["first_func"]) > 0)) {
			$form_valid = false;
			$first_func_valid = false;
		}
		if(!(strlen($_POST["second_func"]) > 0)) {
			$form_valid = false;
			$second_func_valid = false;
		}
		if(!(strlen($_POST["collection"]) > 0)) {
			$form_valid = false;
			$collection_valid = false;	
		}

		echo "<p/>";
		if (!$form_valid){
			echo "<div class=\"row\">";
			echo "<ul class=\"alert alert-danger col-lg-5\" style=\"margin-left:10px;\">";
			if (!$qry_valid) echo "<li style=\"margin-left:10px;\">Please enter a query.</li>";
			if (!$first_func_valid) echo "<li style=\"margin-left:10px;\">Please provide the first retrieval function.</li>";
			if (!$second_func_valid) echo "<li style=\"margin-left:10px;\">Please provide the second retrieval function.</li>";
			if (!$collection_valid) echo "<li style=\"margin-left:10px;\">Please specify a document collection.</li>";
			echo "</ul>";
			echo "</div>";
		}
		else {
			// arrange pagnination
			$results_per_page = 10;
			if (isset($_POST["start"])){
				$start = intval($_POST["start"]);
			} else {
				$start = 0;
			}
			$end = $start + $results_per_page;
			$cur_page = 1+$start/$results_per_page;


			$result_mapping=array();
			$max_number_of_results=0;
			function gen_results($which_one){
				global $start;
				global $end;
				global $result_mapping;
				global $mysql;

				$IndexName=$_POST['collection'];
				$username=$_SESSION['user'];
				$uid=$_SESSION['userID'];
				if($which_one==1){
					$func=$_POST['first_func'];
				} else {
					$func=$_POST['second_func'];
				}
				$RetFun="./users/$uid/retFun/$func.fun";

				$RetProgram="./source/retrieval-flexible-web";
				$compare_root="./users/$uid/compare/";
				$CacheDir="./users/$uid/compare/cache/";
			    $SnippetDir="./users/$uid/compare/snippet/";
			    $GetSnippetProgram="./source/getSnippet";
				$ShowDocPhp="./source/showDoc.php";

				if(!file_exists($compare_root)){
					mkdir($compare_root, 0775, true);
					mkdir($CacheDir, 0775, true);
					mkdir($SnippetDir, 0775, true);
				}

				$query = "select collectionID, indexID from collection where collectionName='$IndexName'";
				$result = $mysql->query($query);
				if(!$result || !($row = $result->fetch_row()))
				{
					echo "Could not locate the index $IndexName<br/>\n";
					return;
				}
				$collectionID=$row[0];
				$IndexID=$row[1];
				$result->free();
				$query = "select indexPath from indexes where indexID='$IndexID'";
				$result = $mysql->query($query);
				if(!$result || !($row = $result->fetch_row()))
				{
					echo "Could not locate the index $IndexName<br/>\n";
					return;
				}
				$IndexPath=$row[0];
				$result->free();

				if ($which_one == 1){
					echo "<div class=\"row\" id=\"compare_results\" style=\"margin-left:5px;margin-top:5px;\">";
				}

				echo "<div class=\"col-lg-6 results\">\n";
				
				$timer = microtime(true);

				$qry = $_POST['qry'];
				$qryTerms=str_replace(" ","_",$_POST['qry']);
				//If had cached before, read cache. Otherwise generate results.
				$cacheFile=$CacheDir.$qryTerms.'-'.$func.'-'.$IndexName;
				if(!file_exists($cacheFile))
				{
					$command = "$RetProgram $IndexPath $RetFun $qry > $cacheFile";
					if(!$file=popen($command,"r")) echo "An error occurred executing the command $command.".str_system_support_err."<br/>\n";
					pclose($file);
				}

				//Read the evaluation if possible
				$qrelTab=array();
				$relN=0;
				$is_officical_query = false;
				$topic;

				$query="select topic,query from qry where collectionID=$collectionID";
				$q_result = $mysql->query($query);
				if(!$q_result) echo "Could not run query.<br/>\n";
				else {
					while($q_row = $q_result->fetch_row()) {
						$this_topic=$q_row[0];
						$this_qry=$q_row[1];
						if(strcmp($qry, $this_qry) == 0) {
							$is_officical_query = true;
							$topic = $this_topic;
							break;
						}
					}
					$q_result->free();
				}	
				
				if($is_officical_query) {
					$query = "select docName,score from qrel where topic='$topic'";
					$result = $mysql->query($query);
			        if(!$result) echo "Could not run query.<br/>\n";
			        else {
				        while($row = $result->fetch_row()) {
							if (!empty($row[0])) {
								$qrelTab[$row[0]]=$row[1];
								if($row[1]>0) $relN++;
							}
						}
						$result->free();
					}
				}

				$docs;
				$docString="";
				$docIDs;
				$scores;
				$snippets;
				$i=0;

				if($is_officical_query && $relN>0) {
					$MAP=0;
					$rnum=0;
					$P30=0;
				}

				$file=fopen($cacheFile,"r") or exit("Unable to open cache file!");
				$retDocN=substr(fgets($file),0,-1);

				while(!feof($file)) {
					$line=fgets($file);
					if(empty($line)) break;
					$split=explode("\t",$line);
					if ($i>=$start && $i<$end) {
						$docs[$i]=$split[1]; //docID
						$docIDs[$i]=$split[2]; //interal docID
						$docString.="_".$split[2]; //inputs of snippet generation program
						$scores[$i]=$split[3];
						if($which_one==1){
							$result_mapping[$docs[$i]]=array($i+1);
						} else {
							if (array_key_exists($docs[$i], $result_mapping)){
								array_push($result_mapping[$docs[$i]], $i+1);
							} else {
								$result_mapping[$docs[$i]]=array(0,$i+1);
							}
						}
					}
					if($is_officical_query && $relN>0) {
						if(isset($qrelTab[$split[1]]) && $qrelTab[$split[1]]>0) {
							$MAP+=($rnum++)/($i+1);
							if($i<=30) $P30++;
						}
					}
					$i++;
				}
				$timer = microtime(true)-$timer;

				if($is_officical_query && $relN>0) {
					echo "<br>";
					echo "<div class=\"row alert alert-info\">";
					if ($which_one == 1) {
						echo "<div class=\"col-lg-7 col-lg-offset-4\" style=\"text-align:right;\"><strong>MAP=".round($MAP/$relN, 5)." &nbsp&nbsp";
					} else {
						echo "<div class=\"col-lg-8\" style=\"margin-left:60px;\"><strong>MAP=".round($MAP/$relN, 5)." &nbsp&nbsp";
					}
					echo "P30=".round($P30/30.0, 5)."</strong></div></div>\n";
				}
				
				global $max_number_of_results;
				if (isset($docs)){
					if($max_number_of_results<$i) {
						$max_number_of_results = $i;
					}
					for($i=0;$i<sizeof($docs);$i++) {
						if($which_one==1){
							echo "<div class=\"r1\">";
						} else {
							echo "<div>";
						}
						echo "<h3 class=\"r\">";
						if($which_one==1) {
							echo ($start+$i+1).".<a target=\"_blank\" href=\"./source/showDoc.php?qry=".$qry."&docID=".$docIDs[$start+$i]."&doc=".$docs[$start+$i]."&index=$IndexID"."\">&nbsp".$docs[$start+$i]."</a></font><br>\n";	
						} else {
							$keys = array_keys($result_mapping[$docs[$start+$i]]);
							if ($result_mapping[$docs[$start+$i]][$keys[0]] == 0){
								if ($is_officical_query){
									if (isset($qrelTab[$docs[$start+$i]])){
										if ($qrelTab[$docs[$start+$i]] > 0){
											$rank_diff_img="./css/up_arrow_correct.png";
										} else {
											$rank_diff_img="./css/up_arrow_wrong.png";
										}
									} else {
										$rank_diff_img="./css/up_arrow_wrong.png";
									}
								} else {
									$rank_diff_img="./css/up_arrow_correct.png";
								}
							} else if($result_mapping[$docs[$start+$i]][$keys[1]] == $result_mapping[$docs[$start+$i]][$keys[0]]){
								$rank_diff_img="./css/equal_arrow.png";
							} else if($result_mapping[$docs[$start+$i]][$keys[1]] > $result_mapping[$docs[$start+$i]][$keys[0]]){
								if ($is_officical_query){
									if (isset($qrelTab[$docs[$start+$i]])){
										if ($qrelTab[$docs[$start+$i]] > 0){
											$rank_diff_img="./css/down_arrow_wrong.png";
										} else {
											$rank_diff_img="./css/down_arrow_correct.png";
										}
									} else {
										$rank_diff_img="./css/down_arrow_correct.png";
									}
								} else {
									$rank_diff_img="./css/down_arrow_correct.png";
								}
							} else if($result_mapping[$docs[$start+$i]][$keys[1]] < $result_mapping[$docs[$start+$i]][$keys[0]]){
								if ($is_officical_query){
									if (isset($qrelTab[$docs[$start+$i]])){
										if ($qrelTab[$docs[$start+$i]] > 0){
											$rank_diff_img="./css/up_arrow_correct.png";
										} else {
											$rank_diff_img="./css/up_arrow_wrong.png";
										}
									} else {
										$rank_diff_img="./css/up_arrow_wrong.png";
									}
								} else {
									$rank_diff_img="./css/up_arrow_correct.png";
								}
							}
							echo ($start+$i+1).".<a target=\"_blank\" href=\"./source/showDoc.php?qry=".$qry."&docID=".$docIDs[$start+$i]."&doc=".$docs[$start+$i]."&index=$IndexID"."\">&nbsp".$docs[$start+$i]."</a>&nbsp&nbsp&nbsp<img src=\"$rank_diff_img\">(".($result_mapping[$docs[$start+$i]][$keys[0]]==0?"NEW":$result_mapping[$docs[$start+$i]][$keys[0]]).")</font><br>\n";	
						}
						echo "</h3>";
						if ($which_one==1){
							echo "<font size=2>".$scores[$start+$i]." </font>&nbsp&nbsp&nbsp";
						}
						if($is_officical_query){
							if(isset($qrelTab[$docs[$start+$i]]))
							{
								if($qrelTab[$docs[$start+$i]]>0){
									echo "<font size=2 color=\"green\"><strong>Relevant(".$qrelTab[$docs[$start+$i]].")</strong></font>";
								} else {
									echo "<font size=2 color=\"grey\">Non-relevant(".$qrelTab[$docs[$start+$i]].")</font>";
								}
							} else {
								echo "<font size=2 color=\"grey\">Non-relevant</font>";
							}
						}
						if ($which_one!=1){
							echo "&nbsp&nbsp&nbsp<font size=2>".$scores[$start+$i]." </font>";
						}
						echo "<br>\n";
						//echo $snippets[$i]."<br>\n";
						echo "</div>\n";
					}
				}
				echo "</div>\n";
			}

			echo "<div class=\"row\">
			<div class=\"col-lg-6\">
			*For <strong>RIGHT</strong> side:
			number in the parenthesis is the rank of first function results. \"NEW\" means this document is not in the first function's results.
			</div>
			<div class=\"col-lg-6\">
			<div class=\"row\">
			<div class=\"col-lg-7\">
			*For TREC queries:<br>
			<img src=\"./css/up_arrow_correct.png\"> boost a relevant document<br>
			<img src=\"./css/up_arrow_wrong.png\"> boost a non-relevant document<br>
			<img src=\"./css/down_arrow_correct.png\"> decrease a non-relevant document<br>
			<img src=\"./css/down_arrow_wrong.png\"> decrease a relevant document<br>
			</div>
			<div class=\"col-lg-5\">
			*For non-TREC queries:<br>
			<img src=\"./css/up_arrow_correct.png\"> boost a document<br>
			<img src=\"./css/down_arrow_correct.png\"> decrease a document<br>
			</div>
			</div>
			</div>
			</div>";
			gen_results(1);
			gen_results(2);


			// Pagination
			//echo "$max_number_of_results";
			//echo "$cur_page";
			//echo "<div class=\"container\">";
			echo "<div class=\"row\">";

			$show_pages = 10;
			$total_pages = ceil($max_number_of_results*1.0/$results_per_page);
			if ($total_pages > $show_pages){
				if($start <= 120){
					echo "<div class=\"col-lg-6\" style=\"padding-left:0px; margin-left:295px;\">";
				} else {
					echo "<div class=\"col-lg-8\" style=\"padding-left:0px; margin-left:275px;\">";
				}
				echo "<ul class=\"pagination\">";

				if ($start == 0){
					echo "<li class=\"disabled\"><a>&laquo;</a></li>";
				} else {
					$p = ($cur_page-2)*$results_per_page;
					echo "<li><a id=\"page_$p\" style=\"cursor:pointer;\" onclick=\"pagination(this.id);\">&laquo;</a></li>";
				}
				if ($cur_page <= $show_pages/2){
					for ($j=1;$j<=$show_pages;$j++){
						if ($j == $cur_page){
							echo "<li class=\"active\"><a>$j</a></li>";
						} else {
							$p = ($j-1)*$results_per_page;
							echo "<li><a id=\"page_$p\" style=\"cursor:pointer;\" onclick=\"pagination(this.id);\">$j</a></li>";
						}
					}
				} else if ($cur_page > ($total_pages-$show_pages/2)																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			){
					for ($j=$total_pages-$show_pages+1;$j<=$total_pages;$j++){
						if ($j == $cur_page){
							echo "<li class=\"active\"><a>$j</a></li>";
						} else {
							$p = ($j-1)*$results_per_page;
							echo "<li><a id=\"page_$p\" style=\"cursor:pointer;\" onclick=\"pagination(this.id);\">$j</a></li>";
						}
					}
				} else {
					for ($j=$cur_page-($show_pages/2)+1;$j<=$cur_page+($show_pages/2);$j++){
						if ($j == $cur_page){
							echo "<li class=\"active\"><a href=\"#\">$j</a></li>";
						} else {
							$p = ($j-1)*$results_per_page;
							echo "<li><a id=\"page_$p\" style=\"cursor:pointer;\" onclick=\"pagination(this.id);\">$j</a></li>";
						}
					}
				}
				if ($start == $max_number_of_results-$results_per_page){
					echo "<li class=\"disabled\"><a>&raquo;</a></li>";
				} else {
					$p = $cur_page*$results_per_page;
					echo "<li><a id=\"page_$p\" style=\"cursor:pointer;\" onclick=\"pagination(this.id);\">&raquo;</a></li>";					
				}
			} else {
				echo "<div class=\"col-lg-8\" style=\"padding-left:0px; margin-left:".(($show_pages-$total_pages)*63)."px;\">";
				echo "<ul class=\"pagination\">";
				for ($j=1;$j<=$total_pages;$j++){
					if ($j == $cur_page){
						echo "<li class=\"active\"><a>$j</a></li>";
					} else {
						$p = ($j-1)*$results_per_page;
						echo "<li><a id=\"page_$p\" style=\"cursor:pointer;\" onclick=\"pagination(this.id);\">$j</a></li>";
					}
				}
			}
			echo "</ul>";
			echo "</div>";
			echo "</div>";
			//echo "</div>";
		}
	}
	?>

	  <!-- Modal -->
	  <div class="modal fade" id="offical_query">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Select a TREC query</h4>
	        </div>
	        <div class="modal-body">
				<div class="accordion" id="oq">
				<?php
					global $mhysql;
					if(($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["collection"]) && strlen($_GET["collection"])>0) 
						|| ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["collection"]) && strlen($_POST["collection"])>0)){
						$IndexName=$_SERVER['REQUEST_METHOD'] === 'GET'?$_GET["collection"]:$_POST["collection"];
						$query="select collectionName from collection where collectionName = '$IndexName'";
						$result = $mysql->query($query);
						if(!$result) echo "Could not run query.<br/>\n";
						$idx = 0;
						while($row = $result->fetch_row())
						{
							$theCollection = $row[0];
							//echo "<h3>$theCollection</h3>\n";
							echo "<div class=\"accordion-group\">
								    <div class=\"accordion-heading\">
								      <a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#oq\" href=\"#collapse$idx\">
								        $theCollection
								      </a>
								    </div>
								    <div id=\"collapse$idx\" class=\"accordion-body collapse\">
								      <div class=\"accordion-inner\">\n";
							$theQuery = "qry_$theCollection";
							//echo "<div>\n";
							$query="select topic,query from $theQuery";
							$result2 = $mysql->query($query);
							if(!$result2) echo "Could not run query.<br/>\n";
							while($row2 = $result2->fetch_row())
							{
								$topic=$row2[0];
								$qry=$row2[1];
								//echo "<a href='search.php?qry=$qry&collection=$theCollection&topic=$topic'>$topic:$qry</a><br/>\n";
								echo "<a class=\"official_q\" value=\"$qry\">$topic:$qry</a><br/>\n";
							}
							$idx++;
							$result2->free();
							echo "</div>
							    </div>
							  </div>\n";
						}
						$result->free();
					} else {
						echo "<div class=\"alert\">Please select a collection first.</div>";
					}
				?>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	      </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	  </div><!-- /.modal -->
	</div>

	</div>
	</div>
	</body>
</html>
