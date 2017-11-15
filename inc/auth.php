<?php //::::::::: HANDLE LOGIN AND LOG OUT :::::::::::::
define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!AJAX_REQUEST) {die();}
header('Content-Type: application/json');
require_once('func.php');

$msg = "Error";
$page = "";
$buttonSelect = $_POST['buttonSelect'];
$pass = false;

//login authentication
if($buttonSelect == 300){

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	try {
	    $db = pdoConnect();
	    $sql = $db->prepare("SELECT `id`,`username`, `password`, `groupid` FROM {$db_table_prefix}users WHERE username = ? ");
		$sql->execute(array($username));
		
		if ($row=$sql->fetch(PDO::FETCH_OBJ)){
		
			//if username is correct than check password
			if (version_compare(phpversion(), "5.5.0", ">=")) { 
			  // you're on 5.5.0 or later 
                if (password_verify($password, $row->password)){$pass = true;}else{$pass = false;}
			}else{
				//for older php versions
                if(crypt($password,$row->password)== $row->password){$pass = true;}else{$pass = false;}
			}
			
			if($pass == true){ 

				//do it untill we get the right session (chances are it will only run once)
				do {
				     $sessionVal = sessionValueCheck();  //get a session value
				} while ($sessionVal != 1);
				

				$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; //check ift's on localhost or domain, get it
				setcookie($COOKIENAME, $sessionVal);
				setcookie($COOKIENAME, $sessionVal, time()+3600);  /* expire in 1 hour */
				setcookie($COOKIENAME, $sessionVal, time()+3600, '/', $domain, false);  //set the cookies
			    
				//insert the session value into database for future reference 
				try {
			    	$db = pdoConnect();
			    	$sql = $db->prepare("UPDATE {$db_table_prefix}users SET session = ? WHERE id = ? ");
					$sql->execute(array("$sessionVal", "$row->id"));
					
					/* Redirect to a different page*/	
					if($row->groupid == 2){ 
						$page = 'dashboard_admin.php'; //admin page
			    	}else{
					     $page = 'dashboard.php';
					}
					$msg ="OK";
					
				}catch(PDOException $e)
			    	{
			    		$msg ='Sorry, something went wrong, please contact administrator.'; //DB issue
			    	}

				$db = null;
			
				
			} else {
				$msg ='Wrong usename or password!';
			}
			
		}else{
			
			$msg ='Wrong usename or password!';
		}
	    
	    
	    }
	catch(PDOException $e)
	    {
	    $msg ="DB Error: ".$e->getMessage();
	    }
	$db = null;
}

//logout
if($buttonSelect == 301){

	//get user ID
        $userID = currentUser();

             	
if($userID['error'] == 0){
	$id = $userID['userid'];
	// unset cookies
	if (isset($_SERVER['HTTP_COOKIE'])) {
	    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
	    foreach($cookies as $cookie) {
	        $parts = explode('=', $cookie);
	        $name = trim($parts[0]);
	        setcookie($name, '', time()-3600);
	        setcookie($name, '', time()-3600, '/');
	    }
	}
	//delete the session on user db too
	try {
    	$db = pdoConnect();
    	$sql = $db->prepare("UPDATE {$db_table_prefix}users SET session = ? WHERE id = ? ");
		$sql->execute(array("", "$id"));
    	  
		  /* Redirect to a different page*/	
			$page = 'index.php';
			$msg ="OK";
    	}
	catch(PDOException $e)
    	{
    		$msg ="DB Error " . $e->getMessage();
    	}

	$db = null;
	
}else{
   /* No session found, expired session so send them to login page -- you can do other things here too!*/	
   $page = 'index.php';
   $msg ="OK";
}
	
}
echo json_encode(array('msg' => $msg, 'page' => $page));
?>
