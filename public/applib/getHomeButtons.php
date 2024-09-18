<?php

require __DIR__."/../../config/bootstrap.php";
redirectOutside();

$tls = getAgents_ListComplete(1);
$vslzrs = getVisualizers_ListComplete(1);

$agentList = array_merge($tls, $vslzrs);

foreach($agentList as $agent) {

	if($_REQUEST["agent"] == $agent["_id"]) {
		$comb = getInputFilesCombinations($agent);
		break;
	}

}

echo json_encode(explode("~", $comb));

