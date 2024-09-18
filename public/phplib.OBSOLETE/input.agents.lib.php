<?php

// UTILITIES
//

// check whether user data can fulfill input requirements of a specific agent
function isUserDataMatchingAgentRequirements($agent_input_files){
	//get agent input requirements based on data_types # TODO consider also formats
	$agent_dts = array();
	foreach ($agent_input_files as $in_name=>$in){
		if ($in['required']){
			foreach ($in['data_type'] as $dt){
				if (!isset($agent_dts[$dt])){
					$agent_dts[$dt]=1;
				}else{
					$agent_dts[$dt]+=1;
				}
			}
		}
	}
	// agents has no compulsory data_type requirements
	if (!$agent_dts){
		return 1;
	}else{
		//get user's files with agent required data_types
		$files_list = getGSFiles_filteredBy(array("data_type" => array('$in' => array_keys($agent_dts)),"visible"   => true));

		if (count($files_list) == 0){
			return 0;
		}else{
			//count num of files with required data_types
			$file_dts = array();
			foreach ($files_list as $f){
				$dt = $f['data_type'];
                                if (!isset($file_dts[$dt])){
                                        $file_dts[$dt]=1;
                                }else{
                                        $file_dts[$dt]+=1;
                                }
                        }
			//if num. of files available of a certain data_type is not enought to run the agent, return 0
			foreach ($agent_dts as $dt => $minimal_agent_count){
				if (!isset($file_dts[$dt]) || $file_dts[$dt] < $minimal_agent_count){
					return 0;
				}
			}
			return 1;
		}
	}

	return 0;
}



// get files from $fileList matching with given file type
function matchFormat_File($type, $fileList) {

	$output = [];	
    // from agent, return empty and select from modal

	if(empty($fileList)) return "";

	// from ws / rerun, match format file with format agent field (type)
	foreach ($fileList as $file) {

		if(isset($file["fn"])) {

			if(preg_grep("/".$file['format']."/i" , $type)) {
		
				$p = explode("/", $file['path']);
                
				$proj = getProject($p[1]);

				$a[0] = $proj['name']. " / $p[2] / $p[3]";
				$a[1] = $file['fn'];

				$output[] = $a;

				//return $output;

			}

		}

	}

	return $output;

}

// format PHP array to JS array
function getArrayJS($array) {

	return preg_replace('/"/', '\'', json_encode($array));

}


// HEADER FUNCTIONS 
//

// check if data request is correct
function InputAgent_checkRequest($request) {
	if (!isset($request['fn']) && !isset($request['rerunDir']) && !isset($request['op'])){
		$_SESSION['errorData']['Error'][]="Please, before running this agent, select the correct files from the workspace or launch agent from the side menu.";
		redirect($GLOBALS["BASEURL"].'workspace/');
	}
}

// get if user is coming from ws, rerun or agent
function InputAgent_getOrigin($request) {

	$from = "";

	// coming from workspace
	if (isset($request['fn'])){
		$from = "workspace";
	}

	// coming from rerun
	if (isset($request['rerunDir'])){
		$from = "rerun";
	}

	// coming from agent
	if (isset($request['op'])){
		$from = "agent";
	}

	return $from;
}

function InputAgent_getPathsAndRerun($request) {

	$output = [];
	$output[0] = [];
	$output[1] = [];

	if ($request['rerunDir']){
		$dirMeta = $GLOBALS['filesMetaCol']->findOne(array('_id' => $request['rerunDir'])); 
		if (!is_array($dirMeta['input_files']) && !isset($dirMeta['arguments'])){
			$_SESSION['errorData']['Error'][]="Cannot rerun job ".$request['rerunDir'].". Some folder metadata is missing.";
			redirect($GLOBALS["BASEURL"].'workspace/');
		}
		if (is_array($dirMeta['input_files'][0])){
			$_SESSION['errorData']['Internal'][]="Cannot rerun job ".$request['rerunDir'].". New directory metadata not implemeted yet.";
			redirect($GLOBALS["BASEURL"].'workspace/');
		}
		foreach ($dirMeta['input_files'] as $fn){
			$file['path'] = getAttr_fromGSFileId($fn,'path');
			$file['fn'] = $fn;
			$file['format'] = getAttr_fromGSFileId($fn,'format');
			array_push($output[1],$file);
		}
		$output[0] = $dirMeta['arguments'];
	}else{
		if (!is_array($request['fn']))
			$request['fn'][]=$request['fn'];

		foreach($request['fn'] as $fn){
			$file['path'] = getAttr_fromGSFileId($fn,'path');
			$file['fn'] = $fn;
			$file['format'] = getAttr_fromGSFileId($fn,'format');
			array_push($output[1],$file);
		}
		//array_push($output[1],getAttr_fromGSFileId($fn,'path'));
	}

	return $output;

}

function InputAgent_getDefExName() {

	// default execution name
	$dirNum="000";
	$dataDirPath = getAttr_fromGSFileId($_SESSION['User']['dataDir'],"path");
	$reObj = new MongoRegex("/^".$dataDirPath."\\/run\d\d\d$/i");
	$prevs  = $GLOBALS['filesCol']->find(array('path' => $reObj, 'owner' => $_SESSION['User']['id']));
	if ($prevs->count() > 0){
					$prevs->sort(array('path' => -1));
					$prevs->next();
					$previous = $prevs->current();
					if (preg_match('/(\d+)$/',$previous["path"],$m) ){
							$dirNum= sprintf("%03d",$m[1]+1);
					}
	}
	$dirName="run".$dirNum;
	$prevs  = $GLOBALS['filesCol']->find(array('path' => $dataDirPath."/$dirName", 'owner' => $_SESSION['User']['id']));
	if ($prevs->count() > 0){
			$dirName="run".rand(100, 999);
	}

	return $dirName;

}

// PROJECTS
//

// get list of projects
function InputAgent_getSelectProjects() {

    $projects = getProjects_byOwner();

    $output = '<select class="form-control" id="select_project" name="project">';
    foreach ($projects as $project_id => $project){
        $selected="";
        $project_code = basename($project['path']);
        if ($project_code == $_SESSION['User']['activeProject'])
            $selected = " selected ";
        $output.="<option value=\"$project_code\" $selected >".$project['name']."</option>";
    }
	$output .='</select>';

	echo $output;	

}

// FORM GENERIC FUNCTIONS
//

// print select file(s)
function InputAgent_printSelectFile($input, $rerun, $ff, $multiple, $required) {

	$req = "field_not_required";
	if($required) $req = "field_required";

	if(!$multiple) {

		$labelopt = (!$required) ? " (optional)" : "";

		$output = '<div class="form-group">
			<label class="control-label">'.$input['description'].$labelopt.' <i class="icon-question agenttips" data-container="body" data-html="true" data-placement="right" data-original-title="<p align=\'left\' style=\'margin:0\'>'.$input['help'].'</p>"></i></label>
			<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-file"></i></span>
					<input type="text" 
						name="visible_'.$input['name'].'" 
						class="form-control form-field-enabled '.$req.'" 
						placeholder="'.$GLOBALS['placeholder_input'].'" 
						value="'.$ff[0].'"
						readonly>
					<input type="hidden" class="form-field-enabled" name="input_files['.$input['name'].']" value="'.$ff[1].'">
					<span class="input-group-btn input-agent">
						<a href="javascript:cleanInput(\'visible_'.$input['name'].'\', \'input_files['.$input['name'].']\', 1);" class="clean-input"><i class="fa fa-times-circle"></i></a>
						<button class="btn green" type="button" 
						onclick="agentModal(\'visible_'.$input['name'].'\', \'input_files['.$input['name'].']\', '.getArrayJS($input['data_type']).', '.getArrayJS($input['file_type']).', false);"><i class="fa fa-check-square-o"></i> Select</button>
					</span>
			</div>
		</div>';

	} else {

		$p = [];
                $r = 0;
                foreach($ff as $fi) {
                	$p[] = $fi[0];
                        $r ++;
                }
		$textarea_height= ($r > 1 ? $r*34 : 34);

		$labelopt = (!$required) ? " (optional)" : "";
	

		$output = '<div class="form-group">
			<label class="control-label">'.$input['description'].$labelopt.' <i class="icon-question agenttips" data-container="body" data-html="true" data-placement="right" data-original-title="<p align=\'left\' style=\'margin:0\'>'.$input['help'].'</p>"></i></label>
			      <div class="input-group">
					<span class="input-group-addon"><i class="fa fa-file"></i></span>
					<textarea 
						name="visible_'.$input['name'].'"
						class="form-control form-field-enabled field_required" 
						style="height:'.$textarea_height.'px"
						placeholder="'.$GLOBALS['placeholder_input'].'" 
						readonly>'.implode("\n", $p).'</textarea>
					<div id="hidden_visible_'.$input['name'].'">';
		foreach($ff as $fi) { 
			$output.='	<input type="hidden" class="form-field-enabled" name="input_files['.$input['name'].'][]" value="'.$fi[1].'">';
		}
		$output.='		</div>
					<span class="input-group-btn input-agent">
						<a href="javascript:cleanInput(\'visible_'.$input['name'].'\', \'input_files['.$input['name'].'][]\', 0);" class="clean-input"><i class="fa fa-times-circle"></i></a>
						<button class="btn green" type="button" 
						onclick="agentModal(\'visible_'.$input['name'].'\', \'input_files['.$input['name'].'][]\','. getArrayJS($input['data_type']).', '.getArrayJS($input['file_type']).', true);"><i class="fa fa-check-square-o"></i> Select</button>
					</span>
			</div>
			</div>';

	}

	echo $output;
}

// print list of files (in select) 
function InputAgent_printListOfFiles($input, $rerun, $required) {

	$req = "field_not_required";
	if($required) $req = "field_required";

	$output = '<div class="form-group">
		<label class="control-label">'.$input['description'].' <i class="icon-question agenttips" data-container="body" data-html="true" data-placement="right" data-original-title="<p align=\'left\' style=\'margin:0\'>'.$input['help'].'</p>"></i></label>
		<div class="input-group">
			<span class="input-group-addon"><i class="fa fa-file"></i></span>
			<select name="input_files_public_dir['.$input['name'].']" class="form-control '.$req.'">
				<option value="">Select the '.$input['description'].'</option>';

				$agent_options = $input['enum_items'];
				for ($i=0; $i<count($agent_options['name']); $i++){

					if($agent_options['name'][$i] == $rerun) $sel = "selected";
					else $sel = "";

					$output .= '<option value="'.$agent_options['name'][$i].'" '.$sel.'>'.$agent_options['description'][$i].'</option>';
				} 

	$output .= '</select>
		</div>
	</div>';

	echo $output;

}

// print input
function InputAgent_printInput($input, $type) {

	$req = "field_not_required";
	if($input["required"]) $req = "field_required";

	$max = $input["maximum"]; 
	$min = $input["minimum"];

	if(isset($max) && isset($min)) {
		
		$range = 'min="'.$min.'" max="'.$max.'"';

	}

	if($type == "number") {

		if(isset($max) && isset($min)) {
			if(($max - $min) > 10) $st = 1;
			if(($max - $min) < 10 && ($max - $min) > 1) $st = 0.1;
			if(($max - $min) < 1) $st = 0.01;
		} else {
			$st = "any";
		}

		$step = 'step="'.$st.'"';

	}

	if(($input['default'] !== null) && ($input['default'] !== "null")) $value = $input['default'];
	else $value = "";

	$output = '<div class="form-group">
				<label class="control-label">'.$input['description'].' <i class="icon-question agenttips" data-container="body" data-html="true" data-placement="right" data-original-title="<p align=\'left\' style=\'margin:0\'>'.$input['help'].'</p>"></i></label>
				<input type="'.$type.'" '.$range.' '.$step.' name="arguments['.$input['name'].']" id="'.str_replace(":", "_", $input['name']).'" class="form-control form-field-enabled '.$req.'" value="'.$value.'">
				</div>';

	return $output;

}

// print input
function InputAgent_printInputHidden($input, $type) {

	$req = "field_not_required";
	if($input["required"]) $req = "field_required";

	if(($input['value'] !== null) && ($input['value'] !== "null")) $value = $input['value'];
	else $value = "";

	$output = '<input type="hidden" name="arguments['.$input['name'].']" id="'.str_replace(":", "_", $input['name']).'" class="form-control '.$req.'" value="'.$value.'">';

	return $output;

}

// print select
function InputAgent_printSelect($input) {

	$req = "field_not_required";
	if($input["required"]) $req = "field_required";

	if(($input['default'] !== null) && ($input['default'] !== "null")) $default = $input['default'];
	else $default = "";

	if($default === "false") $default = [0];
	if($default === "true") $default = [1];

	if($input["type"] == "boolean") {
		$agent_options["name"] = [1, 0];
		$agent_options["description"] = ["True", "False"];
	} else {
		$agent_options = $input['enum_items'];
	}

	$output = '<div class="form-group">
				<label class="control-label">'.$input['description'].' <i class="icon-question agenttips" data-container="body" data-html="true" data-placement="right" data-original-title="<p align=\'left\' style=\'margin:0\'>'.$input['help'].'</p>"></i></label>
				<select  name="arguments['.$input['name'].']" id="'.str_replace(":", "_", $input['name']).'" class="form-control '.$req.'">';
				$sel = "";
				for ($i=0; $i<count($agent_options['name']); $i++) {
					if(($default != "") && in_array($agent_options['name'][$i], $default)) $sel = "selected";
					else $sel = "";
					$output .= '<option value="'.$agent_options['name'][$i].'" '.$sel.'>'.$agent_options['description'][$i].'</option>';
				}
	$output .= '</select>
		</div>';

	return $output;

}

// print select multiple
function InputAgent_printSelectMultiple($input) {

	$req = "field_not_required";
	if($input["required"]) $req = "field_required";

	if(($input['default'] !== null) && ($input['default'] !== "null") && ($input['default'] !== "")) $default = array_values($input['default']);
	else $default = "";

	$agent_options = $input['enum_items'];

	$output = '<div class="form-group">
				<label class="control-label">'.$input['description'].' <i class="icon-question agenttips" data-container="body" data-html="true" data-placement="right" data-original-title="<p align=\'left\' style=\'margin:0\'>'.$input['help'].'</p>"></i></label>
				<select  name="arguments['.$input['name'].'][]" id="'.str_replace(":", "_", $input['name']).'" class="form-control '.$req.'" multiple="multiple">';
				$sel = "";
				for ($i=0; $i<count($agent_options['name']); $i++) {
					if(($default != "") && in_array($agent_options['name'][$i], $default)) $sel = "selected";
					else $sel = "";
					$output .= '<option value="'.$agent_options['name'][$i].'" '.$sel.'>'.$agent_options['description'][$i].'</option>';
				}
	$output .= '</select>
		</div>';

	return $output;

}

// print field
function InputAgent_printField($input, $rerun) {

	if($input["required"]) $req = "field_required";

	switch($input["type"]) {

		case 'string': $field = "input";
									 $type = "text";
									 break;
		case 'enum': 
		case 'boolean': $field = "select";
										break;
		case 'enum_multiple': $field = "select_multiple";
													break;	
		case 'integer':
		case 'number': $field = "input";
									 $type = "number";
									 break;
		case 'hidden': $field = "input";
									 $type = "hidden";
									 break;

	}

	

	switch($field) {

		case "input": if($rerun) $input["default"] = $rerun;
									if($type == "hidden") $output = InputAgent_printInputHidden($input, $type);
									else $output = InputAgent_printInput($input, $type);	
									break;

		case "select": if($rerun) $input["default"] = [$rerun];
									$output = InputAgent_printSelect($input);
									break;

		case "select_multiple": if($rerun) $input["default"] = $rerun;
									$output = InputAgent_printSelectMultiple($input);
									break;

	}

	return $output;

}

// print the whole form for standard agents
function InputAgent_printSettings($arguments, $rerun) {

	$output = '';

	$c = 0;
	foreach($arguments as $arg) {

		if(($c % 2) == 0) $output .= '<div class="row">';

		$output .= '<div class="col-md-6">';
		$output .= InputAgent_printField($arg, $rerun[$arg['name']]);
		$output .= '</div>';

		if(($c % 2) != 0) $output .= '</div>';

		$c ++;

	}

	echo $output;

}


