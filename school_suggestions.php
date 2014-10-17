<?php
$dbValidate = true;
include_once('db.php');

if (isset($_GET['n'])){
	$name = $_GET['n'];
} else {
	die();
}

$returnStr = '';
$subCond = array();
$bind = array();

$result = $db->prepare("SELECT name FROM schools WHERE name like ? LIMIT 10");
$param = '%' . $name . '%';
$result->bind_param('s', $param);
$result->execute();
$result->store_result();
$result->bind_result($schoolName);
while ($result->fetch()){
	$returnStr .= $schoolName.'|';
}

if (strlen($returnStr) > 0){
	$returnStr = substr($returnStr, 0, strlen($returnStr)-1);
}

echo $returnStr;
?>