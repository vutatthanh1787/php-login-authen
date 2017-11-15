<?php
define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!AJAX_REQUEST) {die();}
header('Content-Type: application/json');
require_once('func.php');

$msg = "Error";
$userList = array();
$userData = array();

/* Values received via ajax */
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$title = isset($_POST['title']) ? $_POST['title'] : '';
$username= isset($_POST['username']) ? $_POST['username'] : false;
$fullname= isset($_POST['fullname']) ? $_POST['fullname'] : false;
$email= isset($_POST['email']) ? $_POST['email'] : false;
$password= isset($_POST['password']) ? $_POST['password'] : false;
$phone = isset($_POST['phone']) ? $_POST['phone'] : 0;
$phoneserviceprovider = isset($_POST['phoneserviceprovider']) ? $_POST['phoneserviceprovider'] : '';
$groupid= isset($_POST['groupid']) ? $_POST['groupid'] : 1;


$submitId = isset($_POST['submitId']) ? $_POST['submitId'] : 0;


//add
if($submitId == 1){

$password=securePass($password); //secure it

try {
    $pdo = pdoConnect();
	$insert = $pdo->prepare("INSERT INTO {$db_table_prefix}users(username, fullname, email, password, phone, phoneserviceprovider, title, groupid) 
	VALUES (:username, :fullname, :email, :password, :phone, :phoneserviceprovider, :title, :groupid)");  

	//PDO execute statement
	$insert->execute(array(
	':username' => $username,
	':fullname' => $fullname,
	':email' => $email,
	':password' => $password,
	':phone' => $phone,
	':phoneserviceprovider' => $phoneserviceprovider,
	':title' => $title,
	':groupid' => $groupid));

    $msg = "User added successfully!";
			
}catch (PDOException $e) {
   if ($e->errorInfo[1] == 1062) {
      $msg = "ERROR: Duplicate entry, username or email already used!";
   } else {
      $msg = $groupid."ERROR: Failed to add user!". $e->getMessage();
   }
}

}

//update
if($submitId == 2){

try {
   // update the records
	$pdo = pdoConnect();
	
	//check if passwod is requested to be reset
	if($password != false && !empty($password)){
	  $password=securePass($password); //secure it
	  $sql = "UPDATE {$db_table_prefix}users SET username=?, fullname=?, email=?, password=?, phone=?, phoneserviceprovider=?, title=?, groupid=? WHERE id=?";
	  $arrVal = array($username, $fullname, $email, $password, $phone, $phoneserviceprovider, $title, $groupid,$id);
	}else{
	 $sql = "UPDATE {$db_table_prefix}users SET username=?, fullname=?, email=?, phone=?, phoneserviceprovider=?, title=?, groupid=? WHERE id=?";
	 $arrVal = array($username, $fullname, $email, $phone, $phoneserviceprovider, $title, $groupid,$id);
	}
	
	$q = $pdo->prepare($sql);
	$q->execute($arrVal);
	$msg = "User updated successfully!";
}catch (PDOException $e) {
    $msg = "ERROR: Failed to update user!";
}


}

//delete
if($submitId == 3){

try{
 // delete record
$pdo = pdoConnect();
$sql = "DELETE from {$db_table_prefix}users WHERE id=".$id;
$q = $pdo->prepare($sql);
$q->execute();
$msg = "User removed successfully!";
}catch (PDOException $e) {
    $msg = "ERROR: Failed to delete user!";
} 
}


//User List
if($submitId == 10){
        $id = 0;
	try {
	$pdo = pdoConnect();
	$stmt = $pdo->prepare("SELECT id,username,fullname FROM {$db_table_prefix}users ORDER BY fullname DESC"); 
	$stmt->execute();
	// set the resulting array to associative
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
	$r = $stmt->fetchAll();
		
		$userList[0] = 'Select a user'; //add first select option:
		foreach ($r as $member)
		{
		$userList[$member['id']] = $member['fullname']." - ".$member['username'];
		}
	}catch(PDOException $e) {
	$msg = "Error: getting user list";
	}
	$pdo = null;

}


//User Data
if($submitId == 11 ){

try {
	$pdo = pdoConnect();
	$stmt = $pdo->prepare("SELECT username, fullname, email, password, phone, phoneserviceprovider, title, groupid FROM {$db_table_prefix}users WHERE id='$id' LIMIT 1"); 
	$stmt->execute();
	$row = $stmt->fetch();

		$userData['username'] = $row['username'];
		$userData['fullname'] = $row['fullname'];
		$userData['email'] = $row['email'];
		$userData['phone'] = $row['phone'];
		$userData['phoneserviceprovider'] = $row['phoneserviceprovider'];
		$userData['title'] = $row['title'];
		$userData['groupid'] = $row['groupid'];

	}catch(PDOException $e) {
	$msg = "Error: getting user data";//.$e->getMessage();
	}
	$pdo = null;
}


echo json_encode(array('msg' => $msg,'userList' => $userList,'userData' => $userData));
?>
