<?php

require __DIR__."/../../config/bootstrap.php";


if($_REQUEST){

	$inAgents = $GLOBALS['agentsCol']->findOne(array('_id' => $_REQUEST['agentid']));
	$inAgentsDev = $GLOBALS['agentsDevMetaCol']->findOne(array('_id' => $_REQUEST['agentid']));

	if(isset($inAgents) || isset($inAgentsDev)) {
		$_SESSION['errorData']['Error'][] = "The agent id <strong>".$_REQUEST['agentid']."</strong> is already chosen, please try with another.";
		redirect($GLOBALS['BASEURL'].'admin/newAgent.php');
	} else {
		// insert in agents_dev and agents_dev_meta
		$spec = file_get_contents($GLOBALS['agent_dev_sample']);
		$spec = str_replace("my_agent_id", $_REQUEST['agentid'], $spec);
		$spec = json_decode($spec);

		$io = file_get_contents($GLOBALS['agent_io_dev_sample']);
		$io = str_replace("my_agent_id", $_REQUEST['agentid'], $io);
		$io = json_decode($io);	

		$meta = [
			"_id" => $_REQUEST['agentid'],
			"user_id" => $_SESSION['User']['id'],
			"step1" => [
				"status" => false,
				"date" => date('Y/m/d H:i:s'),
				"agent_io" => $io,
				"agent_io_validated" => false,
				"agent_io_saved" => false,
				"agent_io_files" => false,
				"input_files_combinations" => []
			],
			"step2" => [
				"status" => false,
				"date" => "",
				"type" => "", 
				"agent_code" => ""
			],
			"step3" => [
				"status" => false,
				"date" => "",
				"agent_spec" => $spec,
				"agent_spec_validated" => false,
				"agent_spec_saved" => false
			],
			// status:
			// in_preparation
			// submitted
			// to be revised
			// rejected
			// registered
			"last_status" => "in_preparation",
			"last_status_date" => date('Y/m/d H:i:s'),
			"status_history" => []
		];

		$GLOBALS['agentsDevMetaCol']->insert($meta);

		/*$working_dir = $GLOBALS['dataDir']."/".$_SESSION['User']['id']."/".$GLOBALS['devUser_dir'].$_REQUEST['agentid'];
    $working_dir = preg_replace('#/+#','/',$working_dir);
		if (!is_dir($working_dir)){
			mkpath($working_dir);
		}*/

		generateLogo($_REQUEST['agentid']);

		$_SESSION['errorData']['Info'][] = "The agent <strong>".$_REQUEST['agentid']."</strong> has been created, please check the steps.";
		redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');

	}

}else{
	redirect($GLOBALS['BASEURL']);
}
