<?php                                                                                                                          
                                                                                                                               
require __DIR__."/../../config/bootstrap.php";                                                                                            
//require "../phplib/admin.inc.php";                                                                                                      
                                                                                                                                                      
$data = $GLOBALS['agentsDevMetaCol']->findOne(array('_id' => $_REQUEST['agentid']));                                                                    
                                                                                                                                                      
if(!isset($data)) {                                                                                                                                                  
    $_SESSION['errorData']['Error'][] = "Agent id unexisting.";                                                                                                       
    redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php?id='.$_REQUEST['agentid']);                                                                                    
}                                                                                                                                                                    
                                                                                                                                                                     
if($data["user_id"] != $_SESSION["User"]["id"] && ($_SESSION['User']['Type'] != 0)) {                                                                                
    $_SESSION['errorData']['Error'][] = "The agent id <strong>".$_REQUEST['agentid']."</strong> you are trying to edit doesn't belong to you.";                        
    redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');                                                                                                                               
}                                                                                                                                                                                       
                                                                                                                                                                                        
$ticketnumber = 'VRE-'.rand(1000, 9999);
$subject = 'New agent';

$message = '
    Ticket ID: '.$ticketnumber.'<br>
    User name: '.$_SESSION["User"]["Name"].' '.$_SESSION["User"]["Surname"].'<br>
    User email: '.$_SESSION["User"]["Email"].'<br>
    Request type: '.$subject.'<br>
    Request subject: Creation of new agent <strong>'.$_REQUEST['agentid'].'</strong><br>
    Comments: '.$_REQUEST['comments'];

$messageUser = '
    Copy of the message sent to our technical team:<br><br>
    Ticket ID: '.$ticketnumber.'<br>
    User name: '.$_SESSION["User"]["Name"].' '.$_SESSION["User"]["Surname"].'<br>
    User email: '.$_SESSION["User"]["Email"].'<br>
    Request type: '.$subject.'<br>
    Request subject: Creation of new agent <strong>'.$_REQUEST['agentid'].'</strong><br>
    Comments: '.$_REQUEST['comments'].'<br><br>
    VRE Technical Team';

if(sendEmail($GLOBALS['ADMINMAIL'], "[".$ticketnumber."]: ".$subject, $message, $_SESSION["User"]["Email"])) {

    sendEmail($_SESSION["User"]["Email"], "[".$ticketnumber."]: ".$subject, $messageUser, $_SESSION["User"]["Email"]);

    $GLOBALS['agentsDevMetaCol']->update(array('_id' => $_REQUEST['agentid']),
                                 array('$set'   => array('last_status_date' => date('Y/m/d H:i:s'), 'last_status' => 'submitted')));

    $_SESSION['errorData']['Info'][] = "Agent successfully submitted, we will check it and give you an answer as soon as possible.";
    redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');

} else {

    $_SESSION['errorData']['Error'][] = "Error Submitting agent, please try later.";
    redirect($GLOBALS['BASEURL'].'admin/myNewAgents.php');

}
