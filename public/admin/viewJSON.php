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

switch($_REQUEST["type"]) {

	case "io": echo '<pre>'.json_encode($agentDevJSON["step1"]["agent_io"], JSON_PRETTY_PRINT).'</pre>';
						break;

	case "sp": echo '<pre>'.json_encode($agentDevJSON["step3"]["agent_spec"], JSON_PRETTY_PRINT).'</pre>';
						break;

}

?>
