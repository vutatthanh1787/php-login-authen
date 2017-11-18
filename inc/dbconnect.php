<?php
//Database Information
$db_host = "localhost"; //Host address (most likely localhost)
$db_name = "djkice_members"; //Name of Database
$db_user = "root"; //Name of database user
$db_pass = "root"; //Password for database user
$db_table_prefix = ""; // For better security you can add a prefix for database tables

// PDO connection
function pdoConnect(){
	// Let this function throw a PDO exception if it cannot connect
	global $db_host, $db_name, $db_user, $db_pass;
	try{
		$db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
        $this->logsys .= "Failed to get DB handle: " . $e->getMessage() . "\n";
    }
	return $db;
}

//GLOBAL VARIABLES
$COOKIENAME = 'webtoone';

?>