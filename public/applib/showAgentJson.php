<?php

require __DIR__."/../../config/bootstrap.php";
#require "../phplib/agents.inc.php";
redirectOutside();

print "<h2>Agent configuration file</h2>";

$agentId =  $_REQUEST['agent'];
$agent   = $GLOBALS['agentsCol']->findOne(array('_id' => $agentId));
if (empty($agent)){
	print "<p>The agent '$agentId' is not defined or is not registered in the database. Sorry, cannot show the details for the selected execution</p>";
	die(0);
}
$json = json_encode($agent, JSON_PRETTY_PRINT);

print "<pre style='max-height: calc(100vh - 300px);white-space: pre-wrap;'>$json</pre>";

?>
