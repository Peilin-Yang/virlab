<?php 
  include("include/superHead.php"); 
  include("include/mysql_connect.php");
?>
<!DOCTYPE html>
<html>

    <head>
        <?php include("include/compareHeader.php"); ?>
    </head>

	<body>
	<?php //session_start(); 
	if(!isset($_SESSION['user'])){
		echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
		header('Location: login.php');
	} else {
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			if ((isset($_GET["fid"]) && strlen($_GET["fid"])>0)
				&& (isset($_GET["baseline"]) && strlen($_GET["baseline"])>0)
				&& (isset($_GET["collection"]) && strlen($_GET["collection"])>0)
				&& (isset($_GET["collectionID"]) && strlen($_GET["collectionID"])>0)){
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
	  <li class="active">compare with best for all officical TREC queries</li>
	</ol>

    <form action="./compareBestCollection.php" method="post" class="form-horizontal">
	<div class="row">
		<div class="col-lg-11"> 
		    <select name="second_func" id="select_func2" class="form-control">
		    	<option value="">Choose one of your evaluated functions for collection <?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?></option>
				<?php
				if(isset($_SESSION['user']))
				{
					$mysqlFunc = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'users2',MysqlPort);
					if(!$mysqlFunc) echo "Could not connect to databases.<br/>\n";
					$username=$_SESSION['user'];
					$uid=$_SESSION['userID'];
					$collectionID=$_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collectionID']:$_POST['collectionID'];

					$query= "select * from function where userID = $uid and onlyFlag = 1" ;
					$result = mysqli_query($mysqlFunc,$query);
					if(!$result) echo "Could not run query.<br/>\n";
					else
					{
						//echo "<ul class=\"dropdown-menu\">\n";
						$func_mapping = array();
						while($row = mysqli_fetch_row($result))
						{
							$funcID=$row[0];
							$mysqlEval = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'evaluations2',MysqlPort);
							if(!$mysqlFunc) echo "Could not connect to databases.<br/>\n";
							else {
								$query1= "select * from evaluation where userID = $uid and collectionID = $collectionID" ;
								$result1 = mysqli_query($mysqlEval,$query1);
								if(!$result1) echo "Could not run query.<br/>\n";
								else
								{
									while($row_eval = mysqli_fetch_row($result1)){
           								if ($row_eval[3] === $funcID){
											$funtion_path=$row[4];
											$func_path_split=explode('/', $funtion_path); 
											$retFunOption=substr($func_path_split[sizeof($func_path_split)-1], 0, sizeof($func_path_split[sizeof($func_path_split)-1])-5);

											$func_mapping[$funcID] = $retFunOption;
											if (isset($_POST['second_func'])){
												//echo $retFunOption;
												$request_func=$_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['second_func']:$_POST['second_func'];
												if ($request_func===$funcID){
													echo "<option value=\"$funcID\" selected=\"selected\">$retFunOption</option>";
												} else {
													echo "<option value=\"$funcID\">$retFunOption</option>";
												}
											} else {
												echo "<option value=\"$funcID\">$retFunOption</option>";
											}
										}
									}
									mysqli_free_result($result1);
								}
								mysqli_close($mysqlEval);
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
		    		
		    <div class="col-lg-8" style="padding-top:10px;">
				<div class="form-group">
		    		<label for="select_func1" class="col-lg-4 control-label">Baseline Function:</label>
		    		<div class="col-lg-8">
						<input class="form-control" name="first_func" id="select_func1" type="text" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func']; ?> ?>" disabled>
					</div>
				</div>
			</div>
			<div class="col-lg-4" style="padding-top:10px;">
				<div class="form-group">
		    		<label for="select_collection" class="col-lg-4 control-label">Collection:</label>
		    		<div class="col-lg-8">
						<input class="form-control" name="collection" id="select_collection" type="text" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?>" disabled>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-1">
			<button type="submit" class="btn btn-info btn-compare">Compare</button>
		</div>

		<div class="col-lg-1" style="padding-left:0px;">
			<input class="form-control" name="first_func" id="first_func" type="hidden" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['fid']:$_POST['first_func'] ?>">
			<input class="form-control" name="collectionID" id="collectionID" type="hidden" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collectionID']:$_POST['collectionID']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collectionID']:$_POST['collectionID']; ?>">
			<input class="form-control" name="collection" id="select_collection" type="hidden" value="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?>" placeholder="<?php echo $_SERVER['REQUEST_METHOD'] === 'GET'?$_GET['collection']:$_POST['collection']; ?>">
		</div>

	</div>
	</form>

	<?php 
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$form_valid = true;
		$user_valid = true;
		$first_func_valid = true;
		$second_func_valid = true;
		$collection_valid = true;

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
			echo "<ul class=\"alert alert-danger col-lg-10 col-lg-offset-1\">";
			if (!$first_func_valid) echo "<li class=\"col-lg-offset-1\">Please provide the baseline retrieval function.</li>";
			if (!$second_func_valid) echo "<li class=\"col-lg-offset-1\">Please provide your evaluated retrieval function.</li>";
			if (!$collection_valid) echo "<li class=\"col-lg-offset-1\">Please specify a document collection.</li>";
			echo "</ul>";
			echo "</div>";
		}
		else {
			global $func_mapping;

			$sql_funID = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'users2',MysqlPort);
			if(!$sql_funID) echo "Could not connect to databases.<br/>\n";
			$query = "select * from function where functionID=".$_POST['first_func'];
			$result = mysqli_query($sql_funID,$query);
			if(!$result || !($row = mysqli_fetch_row($result)))
			{
				echo "Could not locate the functionID "+$_POST['first_func']+"<br/>\n";
				return;
			}
			$user1_id=$row[2];
			mysqli_free_result($result);
			mysqli_close($sql_funID);

			$user2 = $_SESSION['user'];
			$user2_id = $_SESSION['userID'];
			$user1_funcID = $_POST["first_func"];
			$user2_funcID = $_POST["second_func"];
			$user2_func = $func_mapping[$_POST["second_func"]];
			$collectionID = $_POST["collectionID"];
			$collectionName = $_POST["collection"];

			$id1 = $user1_funcID;
			$id2 = $user2_func."(".$user2.")";

			$compare_results = array();
			$MAPs = array();
			$qry_mapping = array();
			$mysqlQry = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'collections2',MysqlPort);
			if(!$mysqlQry) echo "Could not connect to databases.<br/>\n";
			$theQuery = "qry_$collectionName";
			$query="select topic,query from $theQuery";
			$result = mysqli_query($mysqlQry,$query);
			if(!$result) echo "Could not run query.<br/>\n";
			else{
				while($row = mysqli_fetch_row($result))
				{
					$topic=$row[0];
					$qry=$row[1];
					$qry_mapping[$topic] = $qry;
				}
				mysqli_free_result($result);
			}
			mysqli_close($mysqlQry);

			$sql_eval = mysqli_connect(MysqlHost,MysqlUser,MysqlPassword,'evaluations2',MysqlPort);
			if(!$sql_eval) echo "Could not connect to databases.<br/>\n";
			else{
				$query="select * from user_$user1_id where collectionID=$collectionID and functionID=$user1_funcID";
				//echo "$query<br>";
				$result=mysqli_query($sql_eval,$query);
				if(!$result) {"Cannot open database!\n";}
				else {
					while ($row = mysqli_fetch_row($result)){
						$topic=$row[2];
						$MAP=round($row[3],4);
						//echo "$topic-".$qry_mapping[$topic].":$MAP<br>";
						$compare_results["$topic:".$qry_mapping[$topic]] = array($id1 => $MAP);
					}
				}
				mysqli_free_result($result); 
				$query="select * from user_$user2_id where collectionID=$collectionID and functionID=$user2_funcID";
				//echo "$query<br>";
				$result=mysqli_query($sql_eval,$query);
				if(!$result) {"Cannot open database!\n";}
				else {
					while ($row = mysqli_fetch_row($result)){
						$topic=$row[2];
						$MAP=round($row[3],4);
						//echo "$topic-".$qry_mapping[$topic].":$MAP<br>";
						$compare_results["$topic:".$qry_mapping[$topic]][$id2] = $MAP;
					}
				}
				mysqli_free_result($result);

				$query_eval="select * from evaluation where userID=$user1_id and collectionID=$collectionID and functionID=$user1_funcID";
				//echo $query_eval;
	            $result_eval = mysqli_query($sql_eval,$query_eval);
	            if(!$result_eval) {
	                echo "Could not get evaluation results list.<br/>\n";
	            } else {
	                $row_eval = mysqli_fetch_row($result_eval);
                    $MAP=$row_eval[4];
	                $MAPs[$id1] = $MAP;
	            }
	            mysqli_free_result($result_eval);

	            $query_eval="select * from evaluation where userID=$user2_id and collectionID=$collectionID and functionID=$user2_funcID";
	            //echo $query_eval;
	            $result_eval = mysqli_query($sql_eval,$query_eval);
	            if(!$result_eval) {
	                echo "Could not get evaluation results list.<br/>\n";
	            } else {
	                $row_eval = mysqli_fetch_row($result_eval);
                    $MAP=$row_eval[4];
	                $MAPs[$id2] = $MAP;
	            }
	            mysqli_free_result($result_eval);

				mysqli_close($sql_eval);
			}

			$map1 = $MAPs[$id1];
			$map2 = $MAPs[$id2];
			echo "
			<div class=\"row alert alert-info\" style=\"margin:0px;\">
			<div class=\"col-lg-4 col-lg-offset-2\">
			<strong>MAP($id1):$map1</strong>
			</div>
			<div class=\"col-lg-4 col-lg-offset-1\">
			<strong>MAP($id2):$map2</strong>
			</div>
			</div>
			<br>
			";

			echo "
			<script>
		    $(document).ready(function(){
		        $('#c1').highcharts({
		            chart: {
		                type: 'bar'
		            },
		            title: {
		                text: 'Comparison of each topic'
		            },
		            xAxis: {
		                categories: [";
		    foreach ($compare_results as $k => $v){
		    	echo "'$k',";
		    }
		    echo    "],
		    				title: {
		                    text: null
		                }
		            },
		            yAxis: {
		                min: 0,
		                title: {
		                    text: 'MAP',
		                    align: 'high'
		                },
		                labels: {
		                    overflow: 'justify'
		                }
		            },
		            tooltip: {
		                valueSuffix: ''
		            },
		            plotOptions: {
		                bar: {
		                    dataLabels: {
		                        enabled: true
		                    }
		                }
		            },
		            legend: {
		                layout: 'vertical',
		                align: 'right',
		                verticalAlign: 'top',
		                x: 0,
		                y: 0,
		                floating: true,
		                borderWidth: 1,
		                backgroundColor: '#FFFFFF',
		                shadow: true
		            },
		            credits: {
		                enabled: false
		            },
		            series: [{
		                name: '$id1',
		                data: [";
		    foreach ($compare_results as $k => $v){
		    	$map=$v[$id1];
		    	echo "$map,";
		    }
		    echo    "]}, {
		                name: '$id2',
		                data: [";
		    foreach ($compare_results as $k => $v){
		    	$map=$v[$id2];
		    	echo "$map,";
		    }
		     echo    "]        
		 			}]
	    		});
		    });
		    </script>
		    ";
			
			$height = sizeof($compare_results)*40;
			echo "<div id=\"c1\" style=\"width:100%; height:".$height."px;\"></div>";
		}
	}
	?>
	</div>
	</div>
	</body>
</html>
