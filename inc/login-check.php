<?php //login check
require_once('func.php');

$userChck = currentUser();

             	
if($userChck['error'] == 0){

/* Redirect to a right page*/	
if($userChck['groupid'] == 2){ 
  $page = 'dashboard_admin.php'; //admin page
}else{
  $page = 'dashboard.php';
}
			
if(basename($_SERVER['PHP_SELF'])==$page){} //do nothing if it's self page
else{header('Location: '.$page);exit;} //else take them to the whatever page
		  
}else{		

if(basename($_SERVER['PHP_SELF'])=='index.php'){ } //do nothing if it's self page
else{ header('Location: index.php'); die();} //else take them to the login page

}

?>