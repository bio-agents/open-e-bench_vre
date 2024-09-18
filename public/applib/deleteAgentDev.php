<?php

require __DIR__."/../../config/bootstrap.php";

redirectOutside();

//
// Delete agent

$r = deleteAgentDev($_REQUEST['agentid']);

if ($r == "0"){
    ?><script type="text/javascript">window.history.go(-1);</script><?php
    exit(0);
}

redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');

?>
