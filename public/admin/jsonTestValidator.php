<?php

require __DIR__."/../../config/bootstrap.php";

redirectAgentDevOutside();

if(!isset($_REQUEST['id'])) {
	$_SESSION['errorData']['Error'][] = "Please provide a agent id.";
	redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');
}

$agentDevJSON = $GLOBALS['agentsDevMetaCol']->findOne(array('_id' => $_REQUEST['id']));

if(!isset($agentDevJSON)) {
	$_SESSION['errorData']['Error'][] = "The agent id <strong>".$_REQUEST['agentid']."</strong> doesn't exist in our database.";
	redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');
}

$agentDevMetaJSON = $GLOBALS['agentsDevMetaCol']->findOne(array('_id' => $_REQUEST['id'], 'user_id' => $_SESSION['User']['id']));

if(!isset($agentDevMetaJSON) && ($_SESSION['User']['Type'] != 0)) {
	$_SESSION['errorData']['Error'][] = "The agent id <strong>".$_REQUEST['agentid']."</strong> you are trying to edit doesn't belong to you.";
	redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');
}

?>

<?php require "../htmlib/header.inc.php"; ?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-container-bg-solid page-sidebar-fixed">
  <div class="page-wrapper">

  <?php require "../htmlib/top.inc.php"; ?>
  <?php require "../htmlib/menu.inc.php"; ?>


<!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                              <li>
                                  <span>Admin</span>
                                  <i class="fa fa-circle"></i>
															</li>
															<li>
                                  <a href="admin/myNewAgents.php">My new agents</a>
                                  <i class="fa fa-circle"></i>
                              </li>
                              <li>
                                  <span>Generate Test Files</span>
                              </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Generate Test Files - define I/O</h1>
                        <!-- END PAGE TITLE-->
												<!-- END PAGE HEADER-->

												<div class="row">
			<div class="col-md-12">
			<?php  
				$error_data = false;
				if ($_SESSION['errorData']){ 
					$error_data = true;
				?>
				<?php if ($_SESSION['errorData']['Info']) { ?> 
					<div class="alert alert-info">
				<?php } else { ?>
					<div class="alert alert-danger">
				<?php } ?>
					
			        <?php 
				foreach($_SESSION['errorData'] as $subTitle=>$txts){
			        	print "<strong>$subTitle</strong><br/>";
				       foreach($txts as $txt){
				       	print "<div>$txt</div>";
					}
				}
		  		unset($_SESSION['errorData']);
		  		?>
			     </div>
			    <?php } ?>
			  </div>
			</div>
						
												<form name="new-agent" id="new-agent" action="applib/updateAgentDevIO.php" method="post" >

                        <div class="row">
                            <div class="col-md-12">
																<p style="margin-top:0;">Paste or write your JSON code in the text area below. Once the JSON is correct, 
you can validate it against our <a href="https://raw.githubusercontent.com/Multiscale-Genomics/VRE_agent_jsons/dev/agent_specification/agent_schema_io.json" target="_blank">JSON Schema</a>. Click the buttons below for further information:</p>

																<p>
																	<a class="btn btn-xs green" href="http://multiscalegenomics.eu/MuGVRE/agent-specification-attributes/" target="_blank"><i class="fa fa-tag" aria-hidden="true"></i> Attribute's help</a>
																	<a class="btn btn-xs green" href="http://multiscalegenomics.eu/MuGVRE/file-types/" target="_blank"><i class="fa fa-list" aria-hidden="true"></i> Available File Types</a>
																	<a class="btn btn-xs green" href="http://multiscalegenomics.eu/MuGVRE/data-types/" target="_blank"><i class="fa fa-list" aria-hidden="true"></i> Available Data Type</a>
																	<a class="btn btn-xs green" href="javascript:;" target="_blank"><i class="fa fa-list" aria-hidden="true"></i> List of keywords</a>
																</p>
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light portlet-fit bordered">
																	
                                    <div class="portlet-body form" id="portlet-json">

																		<input type="hidden" name="agentid" value="<?php echo $_REQUEST['id']; ?>" />

																		<textarea id="code_editor" name="json_agent"  placeholder="Please, paste or write your JSON code here..."><?php echo json_encode($agentDevJSON["step1"]["agent_io"], JSON_PRETTY_PRINT); ?></textarea>
    
																		</div>
                                
                            </div>
														<!-- END EXAMPLE TABLE PORTLET-->
																<input type="hidden" id="base-url" value="<?php echo $GLOBALS['BASEURL']; ?>" />
																<div class="form-actions">
																		<a href="admin/myNewAgents.php" class="btn btn-default">BACK</a>
																		<input type="submit" class="btn green " id="json-val-subm" value="SUBMIT" style="float:right;">
																		<input type="button" class="btn green snd-metadata-btn" id="json-val-but" value="VALIDATE JSON" style="float:right;margin-right:5px;">
																</div>
                        </div>
										</div>
										</form>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->

								<div class="modal fade bs-modal" id="modalJSONSchema" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">JSON Schema Validation</h4>
                            </div>
														<div class="modal-body"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

<?php 

require "../htmlib/footer.inc.php"; 
require "../htmlib/js.inc.php";

?>
