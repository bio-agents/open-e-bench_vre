<?php

// set seccion for each page

$currentSection = '';
$currentSubSection='';
$currentSubSubSection='';
$currentSubSubSubSection='';



switch (pathinfo($_SERVER['PHP_SELF'])['filename']) {

	case 'index':
		if (dirname($_SERVER['PHP_SELF']) == '/home') {
			$currentSection = 'hp';
		} elseif (dirname($_SERVER['PHP_SELF']) == '/helpdesk') {
			$currentSection = 'hd';
		} elseif (dirname($_SERVER['PHP_SELF']) == '/cookies') {
			$currentSection = '';
		} elseif (dirname($_SERVER['PHP_SELF']) == '/launch') {
			$currentSection = 'lt';
		} elseif (dirname($_SERVER['PHP_SELF']) == '/oeb_publish/eudat') {
			$currentSection = 'pb';
			$currentSubSection = 'eudat';
		} else {
			$currentSection = 'uw';
		}
		break;
	case 'input':
	case 'output':
		$currentSection = 'lt';
		break;
	case 'newProject':
		$currentSection = 'uw';
		break;
	case 'editFile':
		$currentSection = 'dt';
		break;
	case 'uploadForm':
	case 'uploadForm2':
		$currentSection = 'dt';
		$currentSubSection = 'lc';
		break;
	case 'general':
		$currentSection = 'he';
		$currentSubSection = 'h1';
		break;
	case 'starting':
		$currentSection = 'he';
		$currentSubSection = 'h2';
		break;
	case 'upload':
		$currentSection = 'he';
		$currentSubSection = 'h3';
		break;
	case 'ws':
		$currentSection = 'he';
		$currentSubSection = 'h4';
		break;
	case 'launch':
		$currentSection = 'he';
		$currentSubSection = 'h5';
		break;
	case 'agents':
		$currentSection = 'he';
		$currentSubSection = 'h6';
		break;
	case 'hdesk':
		$currentSection = 'he';
		$currentSubSection = 'h7';
		break;
	case 'related':
		$currentSection = 'he';
		$currentSubSection = 'h8';
		break;
	case 'refs':
		$currentSection = 'he';
		$currentSubSection = 'h9';
		break;
	case 'ackn':
		$currentSection = 'he';
		$currentSubSection = 'h10';
		break;
	case 'visualizers':
		$currentSection = 'he';
		$currentSubSection = 'h11';
		break;
	case 'datasets':
		$currentSection = 'dt';
		$currentSubSection = 'rp';
		$currentSubSubSection = 'bs';
		break;
	case 'oeb_blocks':
		$currentSection = 'mg';
		$currentSubSection = 'mgb';
		break;
	case 'oeb_workflows':
		$currentSection = 'mg';
		$currentSubSection = 'mgw';
		break;
	case 'oeb_manuals':
		$currentSection = 'mg';
		$currentSubSection = 'mgm';
		break;
	case 'dataFromID':
		$currentSection = 'dt';
		$currentSubSection = 'id';
		break;
	case 'sampleDataList':
		$currentSection = 'dt';
		$currentSubSection = 'sd';
		break;
	case 'usrProfile':
		$currentSection = 'up';
		$currentSubSection = 'mp';
		break;
	case 'dashboard':
		$currentSection = 'ad';
		$currentSubSection = 'ds';
		break;
	case 'adminUsers':
		$currentSection = 'ad';
		$currentSubSection = 'au';
		break;
	case 'adminAgents':
		$currentSection = 'ad';
		$currentSubSection = 'at';
		$currentSubSubSection = 'mt';
		break;
	case 'logs':
		$currentSection = 'ad';
		$currentSubSection = 'at';
		$currentSubSubSection = 'lg';
		break;
	case 'adminJobs':
		$currentSection = 'ad';
		$currentSubSection = 'aj';
		break;
	case 'oeb_newReq':
		$currentSection = 'pb';
		$currentSubSection = 'oeb';
		$currentSubSubSection = 'nr';
		break;
	case 'oeb_manageReq':
		$currentSection = 'pb';
		$currentSubSection = 'oeb';
		$currentSubSubSection = 'mr';
		break;
	case 'jsonTestValidator':
	case 'jsonSpecValidator':
	case 'myNewAgents':
	case 'vmURL':
	case 'newAgent':
	case 'createTest':
		$currentSection = 'ad';
		$currentSubSection = 'at';
		$currentSubSubSection = 'td';
		break;
	case 'help':
		$currentSection = 'he';
		$a = explode("/", dirname($_SERVER['PHP_SELF']));
		if ($a[1] == "agents") {
			$currentSubSection = 'h6';
		} else {
			$currentSubSection = 'h11';
		}
		$currentSubSubSection = $a[sizeof($a) - 2];
		break;
	case 'agenthelp':
		parse_str($_SERVER['QUERY_STRING'], $queries);
		$currentSection = 'he';
		$currentSubSection = 'h6';
		$currentSubSubSection = $queries['agent'];
		$currentSubSubSubSection = substr($queries['sec'], 0, 3);
		break;
	case 'method':
		$currentSection = 'he';
		$currentSubSection = 'h6';
		$a = explode("/", dirname($_SERVER['PHP_SELF']));
		$currentSubSubSection = $a[sizeof($a) - 2];
		$currentSubSubSubSection = 'met';
		break;
	case 'inputs':
		$currentSection = 'he';
		$currentSubSection = 'h6';
		$a = explode("/", dirname($_SERVER['PHP_SELF']));
		$currentSubSubSection = $a[sizeof($a) - 2];
		$currentSubSubSubSection = 'inp';
		break;
	case 'outputs':
		$currentSection = 'he';
		$currentSubSection = 'h6';
		$a = explode("/", dirname($_SERVER['PHP_SELF']));
		$currentSubSubSection = $a[sizeof($a) - 2];
		$currentSubSubSubSection = 'out';
		break;
	case 'results':
		$currentSection = 'he';
		$currentSubSection = 'h6';
		$a = explode("/", dirname($_SERVER['PHP_SELF']));
		$currentSubSubSection = $a[sizeof($a) - 2];
		$currentSubSubSubSection = 'res';
		break;
	case 'tutorials':
		$currentSection = 'he';
		$currentSubSection = 'h6';
		$a = explode("/", dirname($_SERVER['PHP_SELF']));
		$currentSubSubSection = $a[sizeof($a) - 2];
		$currentSubSubSubSection = 'tut';
		break;
	case 'references':
		$currentSection = 'he';
		$currentSubSection = 'h6';
		$a = explode("/", dirname($_SERVER['PHP_SELF']));
		$currentSubSubSection = $a[sizeof($a) - 2];
		$currentSubSubSubSection = 'ref';
		break;
}

// load all agents 
$agents = getAgents_List();
sort($agents);

// load all visualizers 
$visualizers = getVisualizers_List();
sort($visualizers);

?>

<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!--<div class="top-gradient"></div> -->
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<li class="sidebar-toggler-wrapper hide">
					<div class="sidebar-toggler">
						<span></span>
					</div>
				</li>
				<!-- END SIDEBAR TOGGLER BUTTON -->
				<li class="nav-item  <?php if ($currentSection == 'hp') { ?>active open<?php } ?>">
					<a href="home/" class="nav-link nav-toggle">
						<i class="icon-home"></i>
						<span class="title">Homepage</span>
					</a>
				</li>
				<li class="nav-item  <?php if ($currentSection == 'uw') { ?>active open<?php } ?>">
					<a href="workspace/" class="nav-link nav-toggle">
						<i class="icon-screen-desktop"></i>
						<span class="title">User Workspace</span>
					</a>
				</li>
				<li class="nav-item  <?php if ($currentSection == 'dt') { ?>active open<?php } ?>">
					<a href="javascript:;" class="nav-link nav-toggle">
						<i class="icon-cloud-upload"></i>
						<span class="title">Get Data</span>
						<?php if ($currentSection == 'dt') { ?><span class="selected"></span><?php } ?>
						<span class="arrow <?php if ($currentSection == 'dt') { ?>open<?php } ?>"></span>
					</a>
					<ul class="sub-menu">
						<li class="nav-item <?php if ($currentSubSection == 'lc') { ?>active open<?php } ?>">
							<a href="getdata/uploadForm.php" class="nav-link ">
								<span class="title">Upload Files</span>
							</a>
						</li>
						<li class="nav-item <?php if ($currentSubSection == 'rp') { ?>active open<?php } ?>">
							<a href="javascript:;" class="nav-link nav-toggle ">
								<span class="title">From OpenEBench</span>
								<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								<li class="nav-item <?php if ($currentSubSubSection == 'bs') { ?>active open<?php } ?>">
									<a href="getdata/datasets.php" class="nav-link">
										<span class="title"> Datasets </span>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item <?php if ($currentSubSection == 'sd') { ?>active open<?php } ?>">
							<a href="getdata/sampleDataList.php" class="nav-link ">
								<span class="title">Import example dataset</span>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item  <?php if ($currentSection == 'lt') { ?>active open<?php } ?>">
					<a href="launch/" class="nav-link nav-toggle">
						<i class="icon-rocket"></i>
						<span class="title">Run Agent / Visualizer</span>
					</a>
				</li>
				<li class="nav-item  <?php if ($currentSection == 'he') { ?>active open<?php } ?>">
					<a href="javascript:;" class="nav-link nav-toggle">
						<i class="icon-question"></i>
						<span class="title">Help</span>
						<?php if ($currentSection == 'he') { ?><span class="selected"></span><?php } ?>
						<span class="arrow <?php if ($currentSection == 'he') { ?>open<?php } ?>"></span>
					</a>
					<ul class="sub-menu">
						<li class="nav-item  <?php if ($currentSubSection == 'h1') { ?>active open<?php } ?>">
							<a href="help/general.php" class="nav-link ">
								<span class="title">General information</span>
							</a>
						</li>
						<li class="nav-item  <?php if ($currentSubSection == 'h3') { ?>active open<?php } ?>">
							<a href="help/upload.php" class="nav-link ">
								<span class="title">Get Data</span>
							</a>
						</li>
						<li class="nav-item  <?php if ($currentSubSection == 'h5') { ?>active open<?php } ?>">
							<a href="help/launch.php" class="nav-link ">
								<span class="title">Launch Job</span>
							</a>
						</li>
						<li class="nav-item  <?php if ($currentSubSection == 'h6') { ?>active open<?php } ?>">
							<a href="help/agents.php" class="nav-link">
								<span class="title">Agents</span>
								<span class="arrow <?php if ($currentSubSection == 'h6') { ?>open<?php } ?>"></span>
							</a>

							<ul class="sub-menu">
								<?php foreach ($agents as $t) {
									$s = HelpsDAO::selectHelps(array('agent' => $t["_id"]),array("projection" => ['_id' => 1]));
									$sections = $s;
									$sections2 = array_column($s, 'help');
									$arrSect = array();
									foreach ($sections as $sec) {
										$arrSect[] = $sec['help'];
									} ?>
									<li class="nav-item <?php if ($currentSubSubSection == $t["_id"]) { ?>active open<?php } ?>">
										<a href="help/agenthelp.php?agent=<?php echo $t["_id"]; ?>&sec=help" class="nav-link">
											<span class="title"> <?php echo $t["name"]; ?> </span>
											<span class="arrow <?php if ($currentSubSubSection == $t["_id"]) { ?>open<?php } ?>"></span>
										</a>

										<ul class="sub-menu">
											<?php foreach ($sections as $sec) {
												if ($sec['help'] == "help") {
													continue;
												}
											?>
												<li class="nav-item <?php if ($currentSubSubSubSection == substr($sec['help'], 0, 3)) { ?>active open<?php } ?>">
													<a href="help/agenthelp.php?agent=<?php echo $t["_id"]; ?>&sec=<?php echo $sec['help']; ?>" class="nav-link">
														<span class="title"><?php echo $sec['help']; ?></span>
													</a>
												</li>
											<?php } ?>
										</ul>
									</li>
								<?php } ?>
							</ul>

						</li>
					</ul>
				</li>
				<?php if (allowedRoles($_SESSION['User']['Type'], $GLOBALS['ADMIN']) || allowedRoles($_SESSION['User']['Type'], $GLOBALS['TOOLDEV'])) { ?>
					<li class="nav-item  <?php if ($currentSection == 'mg') { ?>active open<?php } ?>">
						<a href="javascript:;" class="nav-link nav-toggle">
							<i class="fa fa-tasks" style="color: #fff;"></i>
							<span class="title">Management</span>
							<?php if ($currentSection == 'mg') { ?><span class="selected"></span><?php } ?>
							<span class="arrow <?php if ($currentSection == 'mg') { ?>open<?php } ?>"></span>
						</a>
						<ul class="sub-menu">
							<li class="nav-item  <?php if ($currentSubSection == 'mgb') { ?>active open<?php } ?>">
								<a href="oeb_management/oeb_block/oeb_blocks.php" class="nav-link ">
									<span class="title">Blocks</span>
								</a>
							</li>
							<li class="nav-item  <?php if ($currentSubSection == 'mgw') { ?>active open<?php } ?>">
								<a href="oeb_management/oeb_block/oeb_workflows.php" class="nav-link ">
									<span class="title">Workflows</span>
								</a>
							</li>
							<li class="nav-item  <?php if ($currentSubSection == 'mgm') { ?>active open<?php } ?>">
								<a href="oeb_management/oeb_block/oeb_manuals.php" class="nav-link ">
									<span class="title">Manuals</span>
								</a>
							</li>
						</ul>
					</li>
				<?php } ?>

				<!-- results section -->
				<?php if (allowedRoles($_SESSION['User']['Type'], $GLOBALS['NO_GUEST'])) { ?>
					<li class="nav-item  <?php if ($currentSection == 'mg') { ?>active open<?php } ?>">
						<a href="javascript:;" class="nav-link nav-toggle">
							<i class="fa fa-list" style="color: #fff;"></i>
							<span class="title">Results</span>
							<?php if ($currentSection == 'mg') { ?><span class="selected"></span><?php } ?>
							<span class="arrow <?php if ($currentSection == 'mg') { ?>open<?php } ?>"></span>
						</a>
						<ul class="sub-menu">
							<li class="nav-item <?php if ($currentSubSection == 'ps') { ?>active open<?php } ?>">
								<a href="javascript:;" class="nav-link nav-toggle ">
									<span class="title">Overview</span>
									<span class="arrow"></span>
								</a>
								<ul class="sub-menu">
									<li class="nav-item <?php if ($currentSubSubSection == 'vl') { ?>active open<?php } ?>">
										<a href="oeb_results/oeb_views/oeb_generalView.php" class="nav-link">
											<span class="title">General</span>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				<?php } ?>
				<!-- publish -->
				<?php if (allowedRoles($_SESSION['User']['Type'], $GLOBALS['NO_GUEST'])) { ?>

					<li class="nav-item  <?php if ($currentSection == 'pb') { ?>active open<?php } ?>">
						<a href="javascript:;" class="nav-link nav-toggle">
							<i class="fa fa-upload" style="color: #fff;"></i>
							<span class="title">Publish</span>
							<?php if ($currentSection == 'pb') { ?><span class="selected"></span><?php } ?>
							<span class="arrow <?php if ($currentSection == 'pb') { ?>open<?php } ?>"></span>
						</a>
						<ul class="sub-menu">
							<li class="nav-item  <?php if ($currentSubSection == 'oeb') { ?>active open<?php } ?>">
								<a href="javascript:;" class="nav-link nav-toggle">
									<span class="title">to OpenEBench</span>
									<span class="arrow <?php if ($currentSubSection == 'oeb') { ?>open<?php } ?>"></span>
								</a>
								<ul class="sub-menu">
									<li class="nav-item  <?php if ($currentSubSubSection == 'nr') { ?>active open<?php } ?>">
										<a href="oeb_publish/oeb/oeb_newReq.php" class="nav-link ">
											<span class="title">New request</span>
										</a>
									</li>
									<li class="nav-item  <?php if ($currentSubSubSection == 'mr') { ?>active open<?php } ?>">
										<a href="oeb_publish/oeb/oeb_manageReq.php" class="nav-link ">
											<span class="title">Manage requests</span>
										</a>
									</li>
								</ul>
							</li>
							<li class="nav-item  <?php if ($currentSubSection == 'eudat') { ?>active open<?php } ?>">
								<a href="oeb_publish/eudat/" class="nav-link ">
									<span class="title">to EUDAT/B2SHARE</span>
								</a>
							</li>
						</ul>
					</li>

				<?php } ?>
				
				<!-- helpdesk -->
				<?php if (allowedRoles($_SESSION['User']['Type'], $GLOBALS['NO_GUEST'])) { ?>
					<li>
					<li class="nav-item <?php if ($currentSection == 'hd') { ?>active open<?php } ?>">
						<a href="helpdesk/" class="nav-link nav-toggle">
							<i class="icon-earphones"></i>
							<span class="title">Helpdesk</span>
						</a>
					</li>
				<?php } ?>
				<?php if (allowedRoles($_SESSION['User']['Type'], $GLOBALS['ADMIN']) || allowedRoles($_SESSION['User']['Type'], $GLOBALS['TOOLDEV'])) { ?>
					<li class="nav-item  <?php if ($currentSection == 'ad') { ?>active open<?php } ?>">
						<a href="javascript:;" class="nav-link nav-toggle">
							<i class="icon-settings"></i>
							<span class="title">Admin</span>
							<?php if ($currentSection == 'up') { ?><span class="selected"></span><?php } ?>
							<span class="arrow <?php if ($currentSection == 'ad') { ?>open<?php } ?>"></span>
						</a>
						<ul class="sub-menu">
							<?php if (!allowedRoles($_SESSION['User']['Type'], $GLOBALS['TOOLDEV'])) { ?>
								<li class="nav-item  <?php if ($currentSubSection == 'ds') { ?>active open<?php } ?>">
									<a href="admin/dashboard.php" class="nav-link ">
										<span class="title">Dashboard</span>
									</a>
								</li>
							<?php } ?>
							<?php if (!allowedRoles($_SESSION['User']['Type'], $GLOBALS['TOOLDEV'])) { ?>
								<li class="nav-item  <?php if ($currentSubSection == 'au') { ?>active open<?php } ?>">
									<a href="admin/adminUsers.php" class="nav-link ">
										<span class="title">Users Administration</span>
									</a>
								</li>
								<li class="nav-item  <?php if ($currentSubSection == 'aj') { ?>active open<?php } ?>">
									<a href="admin/adminJobs.php" class="nav-link ">
										<span class="title">Job Administration</span>
									</a>
								</li>
							<?php } ?>
							<!--<li class="nav-item  <?php if ($currentSubSection == 'at') { ?>active open<?php } ?>">
                                        	<a href="admin/adminAgents.php" class="nav-link ">
                                            	<span class="title">Agent Administration</span>
                                        	</a>
					</li>
					<li class="nav-item  <?php if ($currentSubSection == 'jv') { ?>active open<?php } ?>">
                                        	<a href="admin/jsonValidator.php" class="nav-link ">
                                            	<span class="title">JSON Validator</span>
                                        	</a>
					</li>-->
							<li class="nav-item  <?php if ($currentSubSection == 'at') { ?>active open<?php } ?>">
								<a href="javascript:;" class="nav-link nav-toggle">
									<span class="title">My agents</span>
									<span class="arrow <?php if ($currentSubSection == 'at') { ?>open<?php } ?>"></span>
								</a>
								<ul class="sub-menu">
									<li class="nav-item  <?php if ($currentSubSubSection == 'mt') { ?>active open<?php } ?>">
										<a href="admin/adminAgents.php" class="nav-link ">
											<span class="title">Installed</span>
										</a>
									</li>
									<li class="nav-item  <?php if ($currentSubSubSection == 'td') { ?>active open<?php } ?>">
										<a href="admin/myNewAgents.php" class="nav-link ">
											<span class="title">Development</span>
										</a>
									</li>
									<li class="nav-item  <?php if ($currentSubSubSection == 'lg') { ?>active open<?php } ?>">
										<a href="admin/logs.php" class="nav-link ">
											<span class="title">Logs</span>
										</a>
									</li>
								</ul>
							</li>

						</ul>
					</li>
				<?php } ?>

				<li class="nav-item active open beta-long" style="color:#FFFFFF;margin-left:18px;margin-top:10px;font-size:12px;">This is the 1.1 version of <?php echo $GLOBALS['AppPrefix']; ?> VRE</li>
				<li class="nav-item active open beta-short" style="color:#fff;margin-left:8px;margin-top:10px;font-size:12px;display:none;">v1.1</li>

			</ul>
			<!-- END SIDEBAR MENU -->
			<!-- END SIDEBAR MENU -->

		</div>
		<!-- END SIDEBAR -->
	</div>
	<!-- END SIDEBAR -->
