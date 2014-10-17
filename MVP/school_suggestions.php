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

$searchTerms = explode(' ', $name);
foreach ($searchTerms as $sT){
	$subCond[] = 'name LIKE ?';
	$bind[] = '%'.$sT.'%';
}
$cond = implode(' AND ', $subCond);

$result = $db->prepare("SELECT name FROM schools WHERE $cond LIMIT 10");
call_user_func_array(array($result,"bind_param"), array_merge(array(str_repeat("s", count($bind))), $bind));
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