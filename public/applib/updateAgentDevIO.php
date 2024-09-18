<?php

require __DIR__."/../../config/bootstrap.php";

if($_REQUEST){

	$data_json = json_decode($_REQUEST['json_agent'], true);
	
	if(!isset($data_json["_id"])) {
		$_SESSION['errorData']['Error'][] = "You are not allowed to remove '_id' field.";
		redirect($GLOBALS['BASEURL'].'admin/jsonTestValidator.php?id='.$_REQUEST['agentid']);
	}

	if($data_json["_id"] != $_REQUEST['agentid']) {
		$_SESSION['errorData']['Error'][] = "You are not allowed to change '_id' value.";
		redirect($GLOBALS['BASEURL'].'admin/jsonTestValidator.php?id='.$_REQUEST['agentid']);
	}

	$data = $GLOBALS['agentsDevMetaCol']->findOne(array('_id' => $_REQUEST['agentid']));

	if(!isset($data)) {
		$_SESSION['errorData']['Error'][] = "Agent id unexisting.";
		redirect($GLOBALS['BASEURL'].'admin/jsonTestValidator.php?id='.$_REQUEST['agentid']);
	}

	// Validate
	$validator = new JsonSchema\Validator();
	$validator->check(json_decode($_REQUEST['json_agent']), (object) array('$ref' => 'file://'.$GLOBALS['agent_io_json_schema']));

	if ($validator->isValid()) {
		$json_validated = true;
		$msg = "JSON validated and saved, please complete the form to finish this step.";
	} else {
		$json_validated = false;
		$msg = "Agent specification saved but it doesn't validate against our JSON Schema.";
	}

	//var_dump($validated);die();

	/*$GLOBALS['agentsDevCol']->remove(array('_id'=> $_REQUEST["agentid"]));
	$GLOBALS['agentsDevCol']->insert($data_json);*/

	$GLOBALS['agentsDevMetaCol']->update(array('_id' => $_REQUEST['agentid']),
		array('$set'   => array(
			'last_status_date' => date('Y/m/d H:i:s'), 
			'step1.agent_io' => $data_json, 
			'step1.date' => date('Y/m/d H:i:s'), 
			'step1.agent_io_validated' => $json_validated, 
			'step1.agent_io_saved' => true,
			'step3.agent_spec.input_files' => $data_json["input_files"],
			'step3.agent_spec.input_files_public_dir' => $data_json["input_files_public_dir"],
			'step3.agent_spec.input_files_combinations' => $data_json["input_files_combinations"],
			'step3.agent_spec.arguments' => $data_json["arguments"],
			'step3.agent_spec.output_files' => $data_json["output_files"]
		)));


	$_SESSION['errorData']['Info'][] = $msg;

	if ($validator->isValid()) {
		redirect($GLOBALS['BASEURL'].'admin/createTest.php?id='.$_REQUEST['agentid']);
	} else {
		redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');
	}

	

}else{
	redirect($GLOBALS['BASEURL']);
}
