<?php
include_once('include.php');

require('ods_reader/php-excel-reader/excel_reader2.php');
require('ods_reader/SpreadsheetReader.php');

$Reader = new SpreadsheetReader('schools.ods');
$skipped = false;
foreach ($Reader as $Row){
	if ($skipped){
		$result = $db->prepare("INSERT INTO schools (name) VALUES (?)");
		$result->bind_param('s', $Row[0]);
		$result->execute();
		$result->free_result();
	} else {
		$skipped = true;
	}
}

fin();
?>