<?php

// compute some statistics from VRE.log file
// OBSOLETE 

function get_statistics_agent($agentId,$user="all"){

	// short statistics output
	$statistics = array("Total Jobs" => 0,
			    "&nbsp; - Finished succsessfully" => 0,
			    "&nbsp; - Finished with errors" => 0
			   );
	// statistics outfile

	if ($user=="all"){
		$logFile =  $GLOBALS['logFile'];
		$logFile ="/orozco/services/MuG/VRE.log.1";
		// Total jobs
		$cmd = "grep 'TOOL:$agentId' $logFile";
		exec($cmd, $matchs);
		if (count($matchs) == 0){
			return $statistics;
		}
		$statistics['Total Jobs']=count($matchs);

		// Parse total jobs
		$jobs=array();
		foreach($matchs as $jobLine){
			if (preg_match('/(.*) \| USER:(.*), ID:(.*), LAUNCHER:(.*), TOOL:(.*), PID:(.*)/',$jobLine,$m)){
			    if ($m[6] == 0 ){
				$jobs[$m[5]]["NO_PID_".rand()]= array(
					"start"   => $m[1],
					"login"   => $m[2],
					"user_id" => $m[3],
					"launcher"=> $m[4],
					"finished"=> $m[1],
					"success" => false, 
					"error"   => true 
				);
				$statistics['&nbsp; - Finished with errors']+=1;
			    }else{
				$jobs[$m[5]][$m[6]]= array(
					"start"   => $m[1],
					"login"   => $m[2],
					"user_id" => $m[3],
					"launcher"=> $m[4],
					"finished"=> "-",
					"success" => "-", 
					"error"   => "-" 
				);
			    }
			}else{
			    $_SESSION['errorData']['Warning'][]="Statistics for agent $agentId not correctly computed. LOG file has bad format";
			    return $statistics;
			}
		}
		// Search for FINISHED
		$pids = "JOB ".implode("|JOB ",array_keys($jobs[$agentId]));
		$cmd = "grep -P '$pids' $logFile | sort -u";
		exec($cmd, $matchs1);
		if (count($matchs1) == 0){
			//$_SESSION['errorData']['Warning'][]="Statistics for agent $agentId not correctly computed. LOG file has bad format.";
		}
		foreach($matchs1 as $jobLine){
			if (preg_match('/(.*) \| JOB (.*) FINISHED SUCCESSFULLY/',$jobLine,$m)){
				$statistics['&nbsp; - Finished succsessfully']+=1;
				if ($jobs[$agentId][$m[2]]){
					$jobs[$agentId][$m[1]]['finished']= $m[1];
					$jobs[$agentId][$m[1]]['success'] = true;
					$jobs[$agentId][$m[1]]['success'] = false;
				}
			}elseif (preg_match('/(.*) \| JOB (.*) FINISHED but with error/',$jobLine,$m)){
				$statistics['&nbsp; - Finished with errors']+=1;
				if ($jobs[$agentId][$m[2]]){
					$jobs[$agentId][$m[1]]['finished']= $m[1];
					$jobs[$agentId][$m[1]]['success'] = false;
					$jobs[$agentId][$m[1]]['success'] = true;
				}
			}
		}
	}
	return $statistics;
}
?>
