<?php

require __DIR__."/../config/bootstrap.php";

?>

<html>
<head>
  <meta charset="utf-8" />
  </head>


<?php
// Check if PHP session exists
$r = checkLoggedIn();

// Recover guest user
if ($_REQUEST['id']){
    if(! checkUserLoginExists($_REQUEST['id'])){
        unset($_REQUEST['id']);
    }
    $r = loadUser($_REQUEST['id'],false);
}

// Create guest    
if (!$_REQUEST['id']){

    // Load WS with sample data, if agent requested
    $agent = array();
    $sd   = "";
    if ($_REQUEST['from']){
       $agent = getAgent_fromId($_REQUEST['from'],1);
       if (!isset($agent['_id'])){
          $_SESSION['userData']['Warning'][]="Cannot load '".$_REQUEST['from']."'. Agent not found";
          redirect("../home/redirect.php");
       }
       if (isset($_REQUEST['sd'])){
          $sd = $_REQUEST['sd'];
       }elseif (isset($agent['sampleData'])){
          $sd = $agent['sampleData'];
       }else{
          $sd = $agent['_id'];
       }
    }
       
    // Get access creating an a anonymous guest account
    $r = createUserAnonymous($sd);
    if (!$r)
        exit('Login error: cannot create anonymous VRE user');

    // Redirect to WS with a welcome modal
    if ($_REQUEST['from']){
        redirect("../workspace/?from=".$_REQUEST['from']);
    }
}
redirect($GLOBALS['BASEURL']."home/redirect.php");
//redirect($GLOBALS['BASEURL']);

