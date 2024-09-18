<?php

require __DIR__."/../../config/bootstrap.php";
redirectOutside();


// get help section
$help = (isset($_REQUEST['sec'])? $_REQUEST['sec'] : "help");
// get agent id
if (! isset($_REQUEST['agent']) ){
	$_SESSION['errorData']['error'][]="Cannot find hep page. 'agent' parameter not received";
	redirect($GLOBALS['BASEURL']."help/agents.php");
}
$agent = $_REQUEST['agent'];


// fetch user

$user = $GLOBALS['usersCol']->findOne(array('_id' => $_SESSION['User']['_id']));
if(isset($user["AgentsDev"])) $tdev = $user["AgentsDev"];
else $tdev = array();

if((isset($_SESSION['User']) 
&& ($user['Status'] == 1) 
&& (allowedRoles($user['Type'], $GLOBALS['TOOLDEV'])) 
&& (in_array($agent, $tdev))) ||
(isset($_SESSION['User']) 
&& ($user['Status'] == 1)
&& ($user['Type'] == 0)
)) $developer = true;
else $developer = false;

// fetch help from db

$Parsedown = new Parsedown();
$Parsedown->setBreaksEnabled(true);

$page            = HelpsDAO::selectHelps(array('agent' => $agent, 'help' => $help))[0];
$markdowncontent = $page['content'];
$htmlcontent     = $Parsedown->text($markdowncontent);

// fetch agent object

$agentData = $GLOBALS['agentsCol']->findOne(array('_id' => $agent));

?>

<?php require "../htmlib/header.inc.php"; ?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-container-bg-solid page-sidebar-fixed">
  <div class="page-wrapper">

  <?php require "../htmlib/top.inc.php"; ?>
  <?php require "../htmlib/menu.inc.php"; ?>

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
      <!-- BEGIN CONTENT BODY -->
      <div class="page-content" id="body-help">
	<!-- BEGIN PAGE HEADER-->
	<!-- BEGIN PAGE BAR -->
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<a href="/home/">Home</a>
				<i class="fa fa-circle"></i>
			</li>
			<li>
				<span>Help</span>
				<i class="fa fa-circle"></i>
			</li>
			<li>
				<a href="help/agents.php">Agents</a>
				<i class="fa fa-circle"></i>
			</li>
			<li>
				<a href="help/agenthelp.php?agent=<?php echo $agentData["_id"]; ?>&sec=help"><?php echo $agentData["name"]; ?></a>
			</li>
		</ul>
	</div>
	<!-- END PAGE BAR -->

	<!-- BEGIN PAGE TITLE-->
	<h1 class="page-title"> <span id="tit-static"><?php echo $page['title']; ?></span>
	<?php if($developer) { ?>
		<input type="text" value="<?php echo $page['title'];?>" id="input-tit" style="display:none;width:100%;" />
		<button type="button" id="bt-edit" class="btn green" style="margin-left:20px;">EDIT PAGE</button>
	<?php } ?>
	</h1>
	<!-- END PAGE TITLE-->

	<!-- END PAGE HEADER-->

	<div id="html-content-help"><?php echo $htmlcontent; ?></div>
		<input type="hidden" value="<?php echo $developer;?>" id="developer" />
		<input type="hidden" id="base-url" value="<?php echo $GLOBALS['BASEURL']; ?>"/>
			
		<?php if($developer) { ?>
		    <form id="help-content" action="javascript:;" style="display:none;">
			<input type="hidden" value="<?php echo $page['title'];?>" name="title" id="title" />
			<input type="hidden" value="<?php echo $help;?>" name="help" id="help" />
			<input type="hidden" value="<?php echo $agent;?>" name="agent" id="agent" />	
			<textarea name="content" id="editor"><?php echo $markdowncontent; ?></textarea>
			<button type="submit" class="btn green" style="margin-top:10px;">SAVE</button>
			<button type="button" id="cancel-edit" class="btn default" style="margin:10px 0 0 5px;">CANCEL</button>
		    </form>
		<?php } ?>
	</div>
	<!-- END CONTENT BODY -->
   </div>
   <!-- END CONTENT -->



<?php 

require "../htmlib/footer.inc.php"; 
require "../htmlib/js.inc.php";

?>
