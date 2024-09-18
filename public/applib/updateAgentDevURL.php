<?php

require __DIR__."/../../config/bootstrap.php";

if($_REQUEST){

	$data = $GLOBALS['agentsDevMetaCol']->findOne(array('_id' => $_REQUEST['agentid']));

	if(!isset($data)) {
		$_SESSION['errorData']['Error'][] = "Agent id unexisting.";
		redirect($GLOBALS['BASEURL'].'admin/vmURL.php?id='.$_REQUEST['agentid']);
	}

	$GLOBALS['agentsDevMetaCol']->update(array('_id' => $_REQUEST['agentid']),
                                 array('$set'   => array('last_status_date' => date('Y/m/d H:i:s'), 'step2.date' => date('Y/m/d H:i:s'), 'step2.status' => true, 'step2.type' => $_REQUEST['type'], 'step2.agent_code' => $_REQUEST['vm-code'])));

	$_SESSION['errorData']['Info'][] = "Agent code path successfully saved, please go to next step (agent specification).";
	redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');

}else{
	redirect($GLOBALS['BASEURL']);
}
