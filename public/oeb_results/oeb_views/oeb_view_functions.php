<?php


function getSelectedAgent($agentsList, $requestParamAgent)
{
    // if agent is selected get agent from url param or else set 1st agent in agent list
    $agentSelected = "";
    if (isset($requestParamAgent) && $requestParamAgent != "") {
        $agentSelected = $requestParamAgent;
    } else {
        $agentSelected = $agentsList[0]["_id"];
    }
    return $agentSelected;
};

//not used anymore
function getAllFilesForSelectedAgent($allFiles, $agentsList, $requestParamAgent)
{
    //get selected agent
    $agentSelected = getSelectedAgent($agentsList, $requestParamAgent);

    //filter all files by selectedagent
    //array of filtered agents
    $filesForSelectedAgent = array();
    if ($allFiles && !empty($allFiles)) {
        foreach ($allFiles as $key => $value) {
            if ($value['agent'] == $agentSelected) {
                array_push($filesForSelectedAgent, $value);
            }
        };
    }
    return $filesForSelectedAgent;
}
