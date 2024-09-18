<?php

require __DIR__."/../../config/bootstrap.php";

if($_REQUEST){

	$data_json = json_decode($_REQUEST['json_agent'], true);
	
	if(!isset($data_json["_id"])) {
		$_SESSION['errorData']['Error'][] = "You are not allowed to remove '_id' field.";
		redirect($GLOBALS['BASEURL'].'admin/jsonSpecValidator.php?id='.$_REQUEST['agentid']);
	}

	if($data_json["_id"] != $_REQUEST['agentid']) {
		$_SESSION['errorData']['Error'][] = "You are not allowed to change '_id' value.";
		redirect($GLOBALS['BASEURL'].'admin/jsonSpecValidator.php?id='.$_REQUEST['agentid']);
	}

	$data = $GLOBALS['agentsDevMetaCol']->findOne(array('_id' => $_REQUEST['agentid']));

	if(!isset($data)) {
		$_SESSION['errorData']['Error'][] = "Agent id unexisting.";
		redirect($GLOBALS['BASEURL'].'admin/jsonSpecValidator.php?id='.$_REQUEST['agentid']);
	}

	// Validate
	$validator = new JsonSchema\Validator();
	$validator->check(json_decode($_REQUEST['json_agent']), (object) array('$ref' => 'file://'.$GLOBALS['agent_json_schema']));

	if ($validator->isValid()) {
		$validated = true;
		$msg = "Agent specification complete, please submit agent.";
	} else {
		$validated = false;
		$msg = "Agent specification saved but it doesn't validate against our JSON Schema.";
	}

	/*$GLOBALS['agentsDevCol']->remove(array('_id'=> $_REQUEST["agentid"]));
	$GLOBALS['agentsDevCol']->insert($data_json);*/

	/*$GLOBALS['agentsDevMetaCol']->update(array('_id' => $_REQUEST['agentid']),
                                 array('$set'   => array('step1' => $validated, 'json_validated' => $validated)));*/

	$GLOBALS['agentsDevMetaCol']->update(array('_id' => $_REQUEST['agentid']),
                                 array('$set'   => array('last_status_date' => date('Y/m/d H:i:s'), 'step3.agent_spec' => $data_json, 'step3.date' => date('Y/m/d H:i:s'), 'step3.status' => $validated, 'step3.agent_spec_validated' => $validated, 'step3.agent_spec_saved' => true)));
	
	//$data_json["name"]
	$working_dir = $GLOBALS['dataDir']."/".$_SESSION['User']['id']."/".$GLOBALS['devUser_dir'].$data_json["_id"];
	$working_dir = preg_replace('#/+#','/',$working_dir);
	if (!is_dir($working_dir)){
		mkpath($working_dir);
		generateLogo($_REQUEST['agentid']);
	}

	$_SESSION['errorData']['Info'][] = $msg;
	redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');

}else{
	redirect($GLOBALS['BASEURL']);
}
