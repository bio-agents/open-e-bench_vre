<?php

require __DIR__."/../../../config/bootstrap.php";

redirectOutside();


$agentid = "APAEVAL_2021";


// check if execution is given

if(!isset($_REQUEST['execution'])){
	$_SESSION['errorData']['Error'][]="You should select a project to view results";
	redirect($GLOBALS['BASEURL'].'workspace/');
}

$executions= explode(",",$_REQUEST['execution']);

// find unTARed data in tmp dir
$data_wds      = array(); // list of unTARed temporal directories, one per execution
$data_ids      = array(); // list of file_ids for each temporal directory
$data_pathTemps= array(); // list of files to be passed to the OEB viewer
foreach ($executions as $execution){
	// build temporal directories
	$wd  = $GLOBALS['dataDir'].$_SESSION['User']['id']."/".$_SESSION['User']['activeProject']."/".$GLOBALS['tmpUser_dir']."/outputs_".$execution;
	if (!is_dir($wd)){
		$_SESSION['errorData']['Error'][]="Cannot visualize your results. They are not accessible anymore. Try logging again, please. ($wd)";
		redirect($GLOBALS['BASEURL'].'workspace/');
	}
	array_push($data_wds,$wd);

	// get file_ids from index 
	$indexFile = $wd.'/index';
	$results = file($indexFile);
	array_push($data_ids,$results);

	// prepare data for custom viewer
	$inner_data  = glob("$wd/*", GLOB_ONLYDIR);
	if (!isset($inner_data[0])){
	        $_SESSION['errorData']['Error'][]="Cannot display the run output. Received results do not contain the expected data or are empty.";
	        redirect($GLOBALS['BASEURL'].'workspace/');
	}	
	$viewerfolder = fromAbsPath_toPath($inner_data[0]);
	$pathTemp = 'workspace/workspace.php?op=openPlainFileFromPath&fnPath='.$viewerfolder;
	array_push($data_pathTemps,$pathTemp);
}

// build data-dir for custom viewer
$data_dir = "[\"".implode("\", \"",$data_pathTemps)."\"]";


// get agent metadata
$agent = getAgent_fromId($agentid, 1);

//////////////////////////////////////////////////////////////////// print page

?>

<?php require "../../htmlib/header.inc.php"; ?>


<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-container-bg-solid page-sidebar-fixed">



  <div class="page-wrapper">

  <?php require "../../htmlib/top.inc.php"; ?>
  <?php require "../../htmlib/menu.inc.php"; ?>

<!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                              <li>
                                  <a href="home/">Home</a>
                                  <i class="fa fa-circle"></i>
                              </li>
                              <li>
                                  <a href="workspace/">User Workspace</a>
                                  <i class="fa fa-circle"></i>
                              </li>
                              <li>
                                  <span>Agents</span>
                                  <i class="fa fa-circle"></i>
                              </li>
                              <li>
							  
                                  <span><?php echo $agent["name"]; ?></span>
                              </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Results
                            <small><?php echo $agent["title"]; ?></small>
                        </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <div class="row">
							<div class="col-md-12">
								<p style="margin-top:0;">General Statistics for <strong><?php echo basename($pathTAR); ?></strong> project.</p>
							</div>
			    			<div class="col-md-12">
                            <p>
                            In order to facilitate the interpretation of benchmarking results OpenEbench offers several ways to visualize metrics: <br>
                            In this 2D plot two metrics from challenge <?php echo $agent["title"]; ?> are represented in the X and Y axis, showing the results from the participants in this challenge.
                            The gray line represents the pareto frontier, which runs over the participants showing the best efficiency and the arrow in the plot represents the optimal corner.
                            <br>
                            The blue selection list can be used to switch between the different classification methods / visualization modes (square quartiles, diagonal quartiles and k-means clustering)
                            Along with the chart these results are also transformed to a table which separates the participants in different groups.

                        </p>
			<?php foreach ($data_ids as $results){ ?>
                            <div class="note note-info" style="padding-bottom:7px;">
			    <h4><a href="workspace/workspace.php?op=downloadFile&fn=<?php echo $results[0]; ?>" style="text-decoration:none;"><i class="fa fa-download"></i> Download all the raw data in a compressed tar.gz file </a></h4>
			    </div>
			<?php } ?>
			   				</div>
						</div>

				<div class="row">
			    	<div class="col-md-12">
						<div class="panel-group accordion">
				  			<div class="panel panel-default">
							  <div id="custom_body" data-dir='<?php echo("$data_dir") ?>' x-label="Recall" y-label="Precision" ></div>
	    			</div>
				</div>

		    </div>
		</div>
        	</div>
                <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->

<?php 

require "../../htmlib/footer.inc.php"; 
require "../../htmlib/js.inc.php";

?>



