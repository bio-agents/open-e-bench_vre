<?php

require __DIR__."/../../config/bootstrap.php";

if($_POST){
	//TODO check compulsory field
	if (!$_POST['Surname'] || !$_POST['Name']){
		$_SESSION['errorData']['Error'][] = "Name and Surname are compulsory fields";
		redirect($_SERVER['HTTP_REFERER']);
	}

	if (($_POST['Type'] == 1) && (!$_POST['agents'])) {
		$_SESSION['errorData']['Error'][] = "If Type of user is Agent Dev, you should select at least one agent.";
		redirect($_SERVER['HTTP_REFERER']);
	} 

	$login = $_POST['Email'];
	
	$user = $GLOBALS['usersCol']->findOne(array('_id' => $login));
		
	if ($user['_id']) {
		$newdata = array('$set' => array('Surname' => ucfirst($_POST['Surname']), 'Name' => ucfirst($_POST['Name']), 'Inst' => $_POST['Inst'], 'Country' => $_POST['Country'], 'diskQuota' => $_POST['diskQuota']*1024*1024*1024, 'Type' => $_POST['Type'], 'AgentsDev' => $_POST['agents']));
		$GLOBALS['usersCol']->update(array('_id' => $login), $newdata);
		$_SESSION['errorData']['Info'][] = "User info successfully updated.";
		redirect($_SERVER['HTTP_REFERER']);
	}else{
		$_SESSION['errorData']['Error'][] = "Non existing user, please check your form";
		redirect($_SERVER['HTTP_REFERER']);
	}

}else{
	redirect($GLOBALS['URL']);
}

?>
