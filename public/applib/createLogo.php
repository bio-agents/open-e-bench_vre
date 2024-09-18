<?php

require __DIR__."/../../config/bootstrap.php";

redirectAgentDevOutside();

// Set the content-type
//header('Content-Type: image/png');

$agentid = $_GET["agentid"];

generateLogo($agentid);

redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');
