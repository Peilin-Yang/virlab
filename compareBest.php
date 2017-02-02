<?php 
  include("include/superHead.php"); 
  include("include/mysql_connect.php"); 
?>
<!DOCTYPE html>
<html>

	<head>
            <?php include("include/compareHeader.php"); ?>
	    
            <script>
	    $(document).ready(function(){
			$("#offical_query_tip").hover(function(){
				$("#offical_query_tip").tooltip('show');
				},function(){
				$("#offical_query_tip").tooltip('hide');
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
	    	//alert(start);
	    	var q = "<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['qry']:$_POST['qry']; ?>";
			var func1 = "<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func']; ?>";
			var func2 = $('#select_func2').val();
			var c = $('#select_collection').val();

			$('#qry_input').val(q);
			$('#select_func2').val("<?php echo $_POST['second_func']; ?>");
			$('#start').val(start);

			$('#this_form').submit();
			//alert(q+","+func1+","+func2+","+c);
			//var posting = $.post("compareBest.php", {"start":start,"qry":q,"user":user,"first_func":func1,"second_func":func2,"collection":c});

			/* Put the results in a div 
			posting.done(function( data ) {
				var content = $( data ).find( '#compare_results' );
				$( "#compare_results" ).empty().append( content );
			});*/
	    }
	    </script>
	</head>

	<body>
	<?php //session_start(); 
	if(!isset($_SESSION['user'])){
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
		header('Location: login.php');
	} else {
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			if ((isset($_GET["fid"]) && strlen($_GET["fid"])>0)
				&& (isset($_GET["collection"]) && strlen($_GET["collection"])>0)){
				/*
				$mysqlIndex = mysqli_connect('localhost','usersweb','123456','collections',3666);
				if(!$mysqlIndex) echo "Could not connect to databases.<br/>\n";
				$query = "select * from indexes";
				$result = mysqli_query($mysqlIndex,$query);
				if(!$result){
					echo "Could not locate the index $IndexName<br/>\n";
				} else {
					while($row = mysqli_fetch_row($result)){
						echo $row[0]." ".$row[1]."<br>";
					}
				}
				mysqli_free_result($result);
				mysqli_close($mysqlIndex);
				*/
			} else {
				header('Location: leaderBoard.php');
			}
		} else {
			/*
			echo $_POST["first_func"];
			echo $_POST["collection"];
			*/
		}
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
        <li style="padding:0px;"><a href="leaderBoardPer.php">Per-collection</a></li>
	</ul>
	</div>
	<div class="col-lg-10">

	<ol class="breadcrumb">
	  <li><a href="leaderBoard.php">LeaderBoard</a></li>
	  <li><a href="leaderBoard.php">Summary</a></li>
	  <li class="active">compare with best using entered queries</li>
	</ol>

	<div>
	<form id="this_form" action="./compareBest.php" method="post" class="form-horizontal">
	<div class="row">
	<div class="col-lg-11">
	<div class="row">
		<div class="col-lg-12">
	    	<input id="qry_input" name="qry" class="form-control" type="search" value="<?php if($_SERVER['REQUEST_METHOD'] === 'POST') {echo $_POST["qry"];} if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["qry"])) {echo $_GET["qry"];}?>" placeholder="Type your query here">
	  	</div>
	</div>

	<p/>

	<div class="row">
		<div class="col-lg-3" style="padding-right:0px;">
			<button type="button" data-toggle="modal" href="#offical_query" class="btn btn-success btn-block">Select a TREC query</button>
		</div>

		<div class="col-lg-1" style="padding-left:0px;">
			<button type="button" id="offical_query_tip" class="btn glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="You will be able to see evaluation results when selecting TREC queries."></button>
		</div>
		<div class="col-lg-8" style="padding-left:0px;"> 
		    <select name="second_func" id="select_func2" class="form-control">
		    	<option value="">Choose one of your functions</option>
				<?php
				if(isset($_SESSION['user']))
				{
					$mysqlFunc = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'users2',MysqlPort);
					if(!$mysqlFunc) echo "Could not connect to databases.<br/>\n";
					$username=$_SESSION['user'];
					$uid=$_SESSION['userID'];

					$query= "select * from function where userID = $uid and onlyFlag = 1" ;
					$result = mysqli_query($mysqlFunc,$query);
					if(!$result) echo "Could not run query.<br/>\n";
					else
					{
						//echo "<ul class=\"dropdown-menu\">\n";
						while($row = mysqli_fetch_row($result))
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
						mysqli_free_result($result);
						//echo "</ul>\n";
					}

					mysqli_close($mysqlFunc);
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
		
	<div class="row">
	<div class="col-lg-7" style="padding-top:10px;">
		<div class="form-group">
    		<label for="select_func1" class="col-lg-4 control-label">Baseline Function:</label>
    		<div class="col-lg-8">
				<input class="form-control" name="first_func" id="select_func1" type="text" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func']; ?> ?>" disabled>
			</div>
		</div>
	</div>

	<div class="col-lg-5" style="padding-top:10px;">
		<div name="collection" class="form-group">
    		<label for="select_collection" class="col-lg-3 control-label">Collection:</label>
    		<div class="col-lg-9">
				<input class="form-control" name="collection" id="select_collection" type="text" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?>" disabled>
			</div>
		</div>
	</div>
	</div> 

	</div>
	<div class="col-lg-1">
		<a href="#" id="submit_form_btn" class="btn btn-info btn-compare-best">Compare</a>
	</div>

	<div class="col-lg-1" style="padding-left:0px;">
		<input class="form-control" name="first_func" id="select_func1" type="hidden" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func'] ?>">
		<input class="form-control" name="collection" id="select_collection" type="hidden" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?>">
		<input class="form-control" name="start" id="start" type="hidden" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'POST'?$_POST['start']:""; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'POST'?$_POST['start']:""; ?>">
	</div>

	</div>
	</form>

	<?php 
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
			if (!$second_func_valid) echo "<li style=\"margin-left:10px;\">Please choose one of your retrieval functions.</li>";
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

			$max_number_of_results=0;
			$result_mapping=array();
			$IndexName=$_POST['collection'];

			$mysqlIndex = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'collections2',MysqlPort);
			if(!$mysqlIndex) echo "Could not connect to databases.<br/>\n";	
			$query = "select indexID from collection where collectionName='$IndexName'";
			$result = mysqli_query($mysqlIndex,$query);
			if(!$result || !($row = mysqli_fetch_row($result)))
			{
				echo "Could not locate the index $IndexName<br/>\n";
				return;
			}
			$IndexID=$row[0];
			mysqli_free_result($result);


			function gen_results($which_one){
				global $start;
				global $end;
				global $result_mapping;
				global $IndexName;
				global $IndexID;
				
				if ($which_one == 2){
					$username=$_SESSION['user'];
					$uid=$_SESSION['userID'];
				}
				
				if($which_one==1){
					$sql_funID = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'users2',MysqlPort);
					if(!$sql_funID) echo "Could not connect to databases.<br/>\n";
					$query = "select * from function where functionID=".$_POST['first_func'];
					$result = mysqli_query($sql_funID,$query);
					if(!$result || !($row = mysqli_fetch_row($result)))
					{
						echo "Could not locate the functionID "+$_POST['first_func']+"<br/>\n";
						return;
					}
					$func=$row[4];
					$uid=$row[2];
					$func_path_split=explode('/', $row[4]);
					$func=substr($func_path_split[sizeof($func_path_split)-1], 0, sizeof($func_path_split[sizeof($func_path_split)-1])-5);
					mysqli_free_result($result);
					mysqli_close($sql_funID);
				} else {
					$username=$_SESSION['user'];
					$uid=$_SESSION['userID'];
					$func=$_POST['second_func'];
				}

				$RetFun="./users/$uid/retFun/$func.fun";
				$RetProgram="./source/retrieval-f-qry";
				$compare_root="./users/$uid/compare/";
				$CacheDir="./users/$uid/compare/cache/";
			    $SnippetDir="./users/$uid/compare/snippet/";
			    $GetSnippetProgram="./source/getSnippet";
				$ShowDocPhp="./source/showDoc.php";

				if(!file_exists($compare_root)){
					exec(mkdir($compare_root));
					exec(mkdir($CacheDir));
					exec(mkdir($SnippetDir));
				}

				/*
				$global_compare_root="./global_compare/";
				$gCacheDir=$global_compare_root."cache/";
			    $gSnippetDir=$global_compare_root."snippet/";
			    if(!file_exists($global_compare_root)){
					exec(mkdir($global_compare_root));
					exec(mkdir($gCacheDir));
					exec(mkdir($gSnippetDir));
				}
				*/

				$mysqlIndex = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'collections2',MysqlPort);
				if(!$mysqlIndex) echo "Could not connect to databases.<br/>\n";
				$query = "select indexPath from indexes where indexID='$IndexID'";
				$result = mysqli_query($mysqlIndex,$query);
				if(!$result || !($row = mysqli_fetch_row($result)))
				{
					echo "Could not locate the index $IndexName<br/>\n";
					return;
				}
				$IndexPath=$row[0];
				mysqli_free_result($result);

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
					$command = "$RetProgram $IndexPath $RetFun " . $cacheFile . " " . $qry;
					//echo $command;
					exec($command);
				}

				//Read the evaluation if possible
				$qrelTab=array();
				$relN=0;
				$is_officical_query = false;
				$topic;

				$query="select topic,query from qry_$IndexName";
				$q_result = mysqli_query($mysqlIndex,$query);
				if(!$q_result) echo "Could not run query.<br/>\n";
				while($q_row = mysqli_fetch_row($q_result))
				{
					$this_topic=$q_row[0];
					$this_qry=$q_row[1];
					if(strcmp($qry, $this_qry) == 0){
						$is_officical_query = true;
						$topic = $this_topic;
						break;
					}
				}

				mysqli_free_result($q_result);

				if($is_officical_query)
				{
					$qrelFile="qrel_$IndexName";
					$query = "select docName,score from $qrelFile where topic='".$topic."'";
					$result = mysqli_query($mysqlIndex,$query);
			        if(!$result) echo "Could not run query.<br/>\n";
			        while($row = mysqli_fetch_row($result))
					{
						if (!empty($row[0])){
							$qrelTab[$row[0]]=$row[1];
							if($row[1]>0) $relN++;
						}
					}
					mysqli_free_result($result);
				}

				$docs;
				$docString="";
				$docIDs;
				$scores;
				$snippets;
				$i=0;
				global $result_mapping;

				if($is_officical_query && $relN>0)
				{
					$MAP=0;
					$rnum=0;
					$P30=0;
				}

				$file=fopen($cacheFile,"r") or exit("Unable to open cache file!");
				$retDocN=substr(fgets($file),0,-1);

				while(!feof($file))
				{
					$line=fgets($file);
					if(empty($line)) break;
					$split=explode("\t",$line);
					if ($i>=$start && $i<$end){
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
					if($is_officical_query && $relN>0)
					{
						if(isset($qrelTab[$split[1]]) && $qrelTab[$split[1]]>0)
						{
							$MAP+=($rnum++)/($i+1);
							if($i<=30) $P30++;
						}
					}
					$i++;
				}
				$timer = microtime(true)-$timer;

				if($is_officical_query && $relN>0)
				{
					echo "<br>";
					echo "<div class=\"row alert alert-info\">";
					if ($which_one == 1){
						echo "<div class=\"col-lg-7 col-lg-offset-4\" style=\"text-align:right;\"><strong>MAP=".round($MAP/$relN, 5)." &nbsp&nbsp";
					} else {
						echo "<div class=\"col-lg-8\" style=\"margin-left:60px;\"><strong>MAP=".round($MAP/$relN, 5)." &nbsp&nbsp";
					}
					echo "P30=".round($P30/30.0, 5)."</strong></div></div>\n";
				}
				
				global $max_number_of_results;
				if (isset($docs)){
					if($max_number_of_results<$i){
						$max_number_of_results = $i;
					}
					for($i=0;$i<sizeof($docs);$i++)
					{
						if($which_one==1){
							echo "<div class=\"r1\">";
						} else {
							echo "<div>";
						}
						echo "<h3 class=\"r\">";
						if($which_one==1){
							echo ($start+$i+1).".<a target=\"_blank\" href=\"./source/showDoc.php?qry=".$qry."&docID=".$docIDs[$start+$i]."&doc=".$docs[$start+$i]."&index=$IndexName"."\">&nbsp".$docs[$start+$i]."</a></font><br>\n";	
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
							echo ($start+$i+1).".<a target=\"_blank\" href=\"./source/showDoc.php?qry=".$qry."&docID=".$docIDs[$start+$i]."&doc=".$docs[$start+$i]."&index=$IndexName"."\">&nbsp".$docs[$start+$i]."</a>&nbsp&nbsp&nbsp<img src=\"$rank_diff_img\">(".($result_mapping[$docs[$start+$i]][$keys[0]]==0?"NEW":$result_mapping[$docs[$start+$i]][$keys[0]]).")</font><br>\n";	
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

				mysqli_close($mysqlIndex);
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
					if(($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["collection"]) && strlen($_GET["collection"])>0) 
						|| ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["collection"]) && strlen($_POST["collection"])>0)){
						$IndexName=$_SERVER['REQUEST_METHOD'] === 'GET'?$_GET["collection"]:$_POST["collection"];
						$mysqlCol = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'collections2',MysqlPort);
						if(!$mysqlCol) echo "Could not connect to databases.<br/>\n";
						$query="select collectionName from collection where collectionName = '$IndexName'";
						$result = mysqli_query($mysqlCol,$query);
						if(!$result) echo "Could not run query.<br/>\n";
						$idx = 0;
						while($row = mysqli_fetch_row($result))
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
							$result2 = mysqli_query($mysqlCol,$query);
							if(!$result2) echo "Could not run query.<br/>\n";
							while($row2 = mysqli_fetch_row($result2))
							{
								$topic=$row2[0];
								$qry=$row2[1];
								//echo "<a href='search.php?qry=$qry&collection=$theCollection&topic=$topic'>$topic:$qry</a><br/>\n";
								echo "<a class=\"official_q\" value=\"$qry\">$topic:$qry</a><br/>\n";
							}
							$idx++;
							mysqli_free_result($result2);
							echo "</div>
							    </div>
							  </div>\n";
						}
						mysqli_free_result($result);
						mysqli_close($mysqlCol);
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
