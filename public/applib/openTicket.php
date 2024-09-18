<?php

require __DIR__."/../../config/bootstrap.php";

switch($_REQUEST["Request"]) {
	case 'general': 
		$req = "Technical question";
		break;
	case 'agents': 
		$req = "Issue related with agents";
		break;
	case 'space': 
		$req = "Request to increase disk quota";
		break;
	case 'agentdev': 
		$req = "Request for becoming a agent developer";
		break;


}

$agent_name = '';

if(isset($_REQUEST['Agent'])) {
	$agentProp = $GLOBALS['agentsCol']->findOne(array('_id' => $_REQUEST['Agent']));
	$agentContact = $agentProp["owner"]["contact"];
	$agent_name = ' - '.$agentProp["name"];
}

$ticketnumber = 'VRE-'.rand(1000, 9999);

$message = '
	Ticket ID: '.$ticketnumber.'<br>
	User name: '.$_REQUEST["Name"].'<br>
	User email: '.$_REQUEST["Email"].'<br>
	Request type: '.$req.$agent_name.'<br>
	Request subject: '.$_REQUEST["Subject"].'<br>
	Request message: '.$_REQUEST["Message"];

$messageUser = '
	Copy of the message sent to our technical team:<br><br>
	Ticket ID: '.$ticketnumber.'<br>
	User name: '.$_REQUEST["Name"].'<br>
	User email: '.$_REQUEST["Email"].'<br>
	Request type: '.$req.$agent_name.'<br>
	Request subject: '.$_REQUEST["Subject"].'<br>
	Request message: '.$_REQUEST["Message"].'<br><br>
	MuG VRE Technical Team';
	

if(sendEmail($GLOBALS['ADMINMAIL'], "[".$ticketnumber."]: ".$req." - ".$_REQUEST["Subject"], $message, $_REQUEST["Email"], $agentContact)) {

	sendEmail($_REQUEST["Email"], "[".$ticketnumber."]: ".$req." - ".$_REQUEST["Subject"], $messageUser, $_REQUEST["Email"]);

	$_SESSION['errorData']['Info'][] = "Ticket successfully open, you will receive a response soon.";
	redirect($_SERVER['HTTP_REFERER']);

} else {

	$_SESSION['errorData']['Error'][] = "Error opening ticket, please try again later.";
	redirect($_SERVER['HTTP_REFERER']);

}	
	

