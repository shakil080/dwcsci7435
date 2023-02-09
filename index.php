<?php
//test page

ob_start();
require_once 'database/database.php';
require_once 'database/config.php';

$database = null;
$database = Database::getDatabase($GLOBALS['connection_String'], $GLOBALS['user_name'], $GLOBALS['Password'], $GLOBALS['dataBaseName']);
$userData = $database->GetCollegeData();

print_r($userData);
// echo implode(" ",$userData);

echo "in index page<br>";
echo date('Y-m-d h:i:s');
exit;

?>
