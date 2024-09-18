<?php

require __DIR__."/../../config/bootstrap.php";

redirectOutside();

if(!$_POST){
	//redirect($GLOBALS['URL']);
	echo "Network error, please reload the Workspace";
}

// getting data types of all the selected files
$fdt = getFiles_DataTypes($_REQUEST["fn"]);

// getting all combinations for every agent
$dt = getAgents_DataTypes();
//var_dump($dt);

// getting all possible agents according to the given data types and agents combinations
$agentsList = getAgents_ByDT($dt, $fdt);

// getting id / name pairs for all agents
$agents = getAgents_ListByID($agentsList, 1);

sort($agents);

if(!empty($agents)) {

foreach($agents as $t) { 

	echo '<li>';
	echo '<a href="javascript:runAgent(\''.$t['_id'].'\');" class="'.$t['_id'].'">';
	if (is_file('../agents/'.$t['_id'].'/assets/ws/icon.php'))
		include '../agents/'.$t['_id'].'/assets/ws/icon.php';
	else
		include '../agents/agent_skeleton/assets/ws/icon.php';
	echo ' '.$t['name'];
	echo '</a>';
	echo '</li>';

}

}else{

	echo '<li>';
	echo '<a href="javascript:;" style="mouse:default;"><i class="fa fa-exclamation-triangle"></i> No agents available for this combination of files</a>';
	echo '</li>';

}


