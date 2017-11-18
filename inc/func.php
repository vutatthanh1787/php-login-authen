<?php

require_once('dbconnect.php'); //get DB conn

function currentUser(){
$session = '';
$results['userid'] = "";
$results['session'] = "";
$results['groupid'] = "";
$results['error'] = 0;

if(isset($_COOKIE['webtoone'])) {
$session = trim($_COOKIE['webtoone']); //get individual cookie

try {
    $db = pdoConnect();
    $sql = $db->prepare("SELECT `id`,`session`,`groupid` FROM users WHERE session = ? ");
	$sql->execute(array("$session"));
	$row=$sql->fetch(PDO::FETCH_OBJ);

	if(strlen($session) != 0 AND strlen($row->session) != 0 AND $session == $row->session){
	      
		      //access this variable in anypage if needed like this ->  $GLOBALS['userid'];
              $userid=$row->id;
              $results['userid'] = $row->id;
			  $results['session'] = $row->session;
			  $results['groupid'] = $row->groupid;
			  

	}else{$results['error'] = 1;}
	
    $db = null;
    }
catch(PDOException $e)
    {
    $results['error'] = 1; // $e->getMessage();
    $db = null; 
    }

}else{
 $results['error'] = 1;
}

return $results;

}

//SECURE PASS HASH/SALT and all that good stuff
function securePass($password){

	if (version_compare(phpversion(), "5.5.0", ">=")) { 
	  // you're on 5.5.0 or later 
	   $pass=password_hash($password, PASSWORD_DEFAULT);
	} else {
		//for older php versions
	   $pass=blowfishCrypt($password,12); 
	   //if(crypt('password',$hash)==$hash){ /*ok*/ } //This checks a password
	}

     return $pass;
}
function blowfishCrypt($password,$cost)
{
    $chars='./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $salt=sprintf('$2y$%02d$',$cost);
//For PHP < PHP 5.3.7 use this instead
//    $salt=sprintf('$2a$%02d$',$cost);
    //Create a 22 character salt -edit- 2013.01.15 - replaced rand with mt_rand
    mt_srand();
    for($i=0;$i<22;$i++) $salt.=$chars[mt_rand(0,63)];
    return crypt($password,$salt);
}
//END SECURE PASS:::

//get ip address
//taken from https://gist.github.com/cballou/2201933
function get_ip_address() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}

//GET THE right session for login
function sessionValueCheck(){
	$len = rand(15,27); // randomized length of the string
	$session = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))),1,$len); //generate a random session
	
	//check is session by any chance already exists:::
	$db = pdoConnect();
	$sql = $db->prepare("SELECT COUNT(*) FROM {$db_table_prefix}users WHERE session = ? LIMIT 1");
	$sql->execute(array("$session"));
	
	if($sql->fetchColumn()){$result = 1;}
	else{$result = $session;}
	$db = null;
	
	return $result;
}


//validate ip address
function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}

//getuseragent
//taken from comments @ php.net
function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent))  { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        //no match
    }
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){ $version= $matches['version'][0];}else{ $version= $matches['version'][1];}
    }
    else { $version= $matches['version'][0];}
    if ($version==null || $version=="") {$version="?";}
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

?>