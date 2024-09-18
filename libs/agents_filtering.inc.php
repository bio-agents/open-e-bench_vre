<?php

////////////////////////////////////////////
/////// DATATPYE INPUT FILE COMBINATIONS
////////////////////////////////////////////

// return datatype for a list of files

function getFiles_DataTypes($fn) {

	$fdt = $GLOBALS['filesMetaCol']->find(array('_id' => array('$in' => $fn)), array("_id" => false, "data_type" => true));

	$a = array();

	foreach($fdt as $v) $a[] = $v["data_type"];	

	return $a;

}

// return datatype for a given agentId

function getDT_byFT_DEPRECATED($id) {

	$dt = $GLOBALS['agentsCol']->find(array("_id" => $id), array("input_files" => true));

	$arr_dt = [];

	foreach($dt as $agent) {
		foreach($agent["input_files"] as $t) {
			$arr_dt = array_merge($arr_dt, $t["data_type"]);
		}
	}
	$arr_dt = array_unique($arr_dt);

	$array = [];
	$array["id"] = $id;
	$array["list1"] = array();
	$array["list2"] = array();
}


// FILTER TOOLS


// return valid combination of input file datatypes for all agents

function getAgents_DataTypes() {

	$dt = $GLOBALS['agentsCol']->find(array("external" => true), array("input_files_combinations_internal" => true));

	$array = array();

	$c = 0;

	foreach($dt as $agent) {

		foreach($agent["input_files_combinations_internal"] as $combination) {

			// crear list 3 pels casos específics?????

			$array[$c]["id"] = $agent["_id"];
			$array[$c]["list1"] = array();
			$array[$c]["list2"] = array();
			$array[$c]["list3"] = array();

			foreach($combination as $single_c) {

				foreach($single_c as $k => $v) {

					// one item
					if($v == 1) $array[$c]["list1"][] = $k;

					// one or more (n mandatory)
					if($v == "+") {
						$array[$c]["list1"][] = $k;
						$array[$c]["list2"][] = $k;
					}

					// 0 or more (n non mandatory)
					if($v == "*") $array[$c]["list2"][] = $k;

					// special cases where no combinations internal are possible
					if($v == "-") $array[$c]["list3"][] = $k;

				}

			}

			$c ++;

		}

	}

	return $array;

}

// return valid combination of input file datatypes for a given agent

function getAvailableDTbyAgent($agent) {

	$dt = $GLOBALS['agentsCol']->find(array("external" => true, "_id" => $agent), array("input_files_combinations_internal" => true))->toArray();

	$array = array();

	$c = 0;

	foreach($dt as $agent) {

		foreach($agent["input_files_combinations_internal"] as $combination) {

			$array[$c]["id"] = $agent["_id"];

			foreach($combination as $single_c) {

				foreach($single_c as $k => $v) {

					$array[$c]["list"][] = $k;

				}

			}

		}

	}

	$array[0]["list"] = array_unique($array[0]["list"]);

	$array = $array[0];

	return $array;

}

// return agents valid for a list of given files

function getAgents_ByDT($agentsDT, $filesDT) {

	$agentsList = array();

	// foreach de totes les possible combinacions de agents
	foreach($agentsDT as $tdt) {
		
		// només entrem si hi ha igual o més fitxers seleccionats que a list1 (obligatoris)
		if(sizeof($tdt["list1"]) <= sizeof($filesDT)) {

			$list1 = $tdt["list1"];
			$list2 = $tdt["list2"];
			$list3 = $tdt["list3"];
			$listF = $filesDT;

			// foreach de tots els fitxers seleccionats
			foreach($listF as $itemWS) {

				// if item is in list1, unset
				if(in_array($itemWS, $list1)) {

					$key = array_search($itemWS, $list1);
					unset($list1[$key]);

					$key = array_search($itemWS, $listF);
					unset($listF[$key]);

				} /*else*/
				if(in_array($itemWS, $list2)) {
					// només que n'hi hagi un els esborrem tots???

					$key = array_search($itemWS, $list2);

					unset($list2[$key]);

					/*$key = array_search($itemWS, $listF);
					unset($listF[$key]);*/

					/*if (($key = array_search($itemWS, $listF)) !== false) {
						unset($listF[$key]);
					}*/

					$listF = array_diff($listF, [$itemWS]);

				} 
				if(in_array($itemWS, $list3)) {
					$agentsList[] = $tdt["id"];
				}
	
				/*else {
				
					break;

					}*/

				//if(!in_array($itemWS, $list1) && !in_array($itemWS, $list2)) break;

			}
			
			// if no more items on both lists, it means matching!
			if((sizeof($list1) == 0) && (sizeof($list2) == 0) && (sizeof($listF) == 0)) $agentsList[] = $tdt["id"];
		}

	}

	$agentsList = array_unique($agentsList);

	return $agentsList;

}

function getAgents_ListByID($array, $status) {

	$array = array_values($array);

	$tl = $GLOBALS['agentsCol']->find(array('_id' => array('$in' => $array), 'status' => array('$in' => [$status, 3])), array("name" => true, "status" => true));

	if($_SESSION['User']['Type'] == 1) {

		 $agents_list = iterator_to_array($tl, false);

		 foreach($agents_list as $key => $agent) {

			 if($agent["status"] == 3 && !in_array($agent["_id"], $_SESSION['User']["AgentsDev"])) {
				 unset($agents_list[$key]);
			 }

		 }

			return $agents_list;

	 } else {

		return iterator_to_array($tl, false);

	 }

}

function getSingleAgent_Help($agentID, $op) {

	$agent = $GLOBALS['agentsCol']->findOne(array("external" => true, "_id" => $agentID), array("input_files_combinations" => true, "input_files" => true));

	$c = 0;

	if (!isset($agent["input_files_combinations"])){
		$_SESSION['errorData']['Error'][]="TOOL ".$agent['_id']." no internal comb";
		next;
	}

	foreach($agent["input_files_combinations"] as $combination) {

		if($c == $op) {

			$array[$c]["id"] = $agent["_id"];
			$array[$c]["operation"] = $combination["description"];
			$array[$c]["content"] = [];

			foreach($agent["input_files"] as $inputf) {

				if(in_array($inputf["name"], $combination["input_files"])) {
					$a = [];
					$a["description"] = $inputf["description"];

					if ($inputf["allow_multiple"] === true)	$a["description"] .= " <span title='Multiple files of this type accepted' style='color:#6d91b5;font-size:0.9em;'>(multiple)</span>";

					if ($inputf["required"] === true) $a["description"] .= " <span title='Mandatory file' style='color:#6d91b5;font-size:0.9em;'>(mandatory)</span>";
					else $a["description"] .= " <span title='Optional file' style='color:#6d91b5;font-size:0.9em;'>(optional)</span>";

					$a["format"] = $inputf["file_type"];

					$b = [];
					foreach($inputf["data_type"] as $dt) {

						$b[] = getDataTypeName($dt);
						//$n  = iterator_to_array($n, true);
						//$b[] = $n[$dt]["name"];

					}

					$a["data_type"] = $b;

					$array[$c]["content"][] = $a;
				}

			}

		}

		$c ++;

	}

	return $array;

}

function getAgents_Help() {

	$dt = $GLOBALS['agentsCol']->find(array("external" => true), array("input_files_combinations" => true, "input_files" => true));

	$array = array();

	$c = 0;

	foreach($dt as $agent) {

		if (!isset($agent["input_files_combinations"])){
			$_SESSION['errorData']['Error'][]="TOOL ".$agent['_id']." no internal comb";
			next;
		}

		foreach($agent["input_files_combinations"] as $combination) {

			$array[$c]["id"] = $agent["_id"];
			$array[$c]["operation"] = $combination["description"];
			$array[$c]["content"] = [];

			foreach($agent["input_files"] as $inputf) {

				if(in_array($inputf["name"], $combination["input_files"])) {
					$a = [];
					$a["description"] = $inputf["description"];

					if ($inputf["allow_multiple"] === true) $a["description"] .= " <span title='Multiple files of this type accepted' style='color:#6d91b5;font-size:0.9em;'>(multiple)</span>";

					if ($inputf["required"] === true) $a["description"] .= " <span title='Mandatory file' style='color:#6d91b5;font-size:0.9em;'>(mandatory)</span>";
					else $a["description"] .= " <span title='Optional file' style='color:#6d91b5;font-size:0.9em;'>(optional)</span>";

					$a["format"] = $inputf["file_type"];

					$b = [];
					foreach($inputf["data_type"] as $dt) {

						$b[] = getDataTypeName($dt);
						//$n  = iterator_to_array($n, true);
						//$b[] = $n[$dt]["name"];

					}

					$a["data_type"] = $b;

					$array[$c]["content"][] = $a;
				}

			}

			$c ++;

		}

	}

	return $array;

}

// FILTER VISUALIZERS

function getFiles_FileTypes($fn) {

	$fdt = $GLOBALS['filesMetaCol']->find(array('_id' => array('$in' => $fn)), array("_id" => false, "format" => true));

	$a = array();

	foreach($fdt as $v) $a[] = $v["format"];	

	return $a;

}

function getVisualizers_FileTypes() {

	$dt = $GLOBALS['visualizersCol']->find(array("external" => true), array("accepted_file_types" => true));

	$array = array();

	foreach($dt as $viewer) {

		$array[] = $viewer;

	}

	return $array;

}

function getVisualizers_ByFT($visFT, $filesFT) {
	
	$visualizersList = array();

	foreach($visFT as $vft) {

		$accepted = false;

		if(count(array_intersect($filesFT, $vft["accepted_file_types"])) == count($filesFT)) {

			$visualizersList[] = $vft["_id"];

		}

	}

	return $visualizersList;

}

function getVisualizers_ListByID($array) {

	$tl = $GLOBALS['visualizersCol']->find(array('_id' => array('$in' => $array)), array("name" => true));

	return iterator_to_array($tl, false);

}
