<?php

ob_start();

require_once 'database/database.php';
require_once 'database/config.php';

if(!session_id()) session_start ();

$data = json_decode(file_get_contents('php://input'), true);
// print_r($data);
$sqlstr = $data["sql"];
// echo $sqlstr;


$database = null;
$database = Database::getDatabase($GLOBALS['connection_String'], $GLOBALS['user_name'], $GLOBALS['Password'], $GLOBALS['dataBaseName']);
$resultData = $database->GetQueryResult($sqlstr);

echo $resultData;
?>
