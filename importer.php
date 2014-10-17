<?php
include_once('include.php');

require('ods_reader/php-excel-reader/excel_reader2.php');
require('ods_reader/SpreadsheetReader.php');

$Reader = new SpreadsheetReader('clery.ods');
$skipped = false;
foreach ($Reader as $Row){
	if ($skipped){
		$result = $db->prepare("INSERT INTO reported_data (year,on_or_off_campus,INSTNM,branch,address,city,state,zip,sector_cd,sector_desc,men_total,women_total,pop_total,forcib,nonfor,total_incidents,campus_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$result->bind_param('isssssssisiiiiiii', $Row[0], $Row[1], $Row[2], $Row[3], $Row[4], $Row[5], $Row[6], $Row[7], $Row[8], $Row[9], $Row[10], $Row[11], $Row[12], $Row[13], $Row[14], $Row[15], $Row[16]);
		$result->execute();
		$result->free_result();
	} else {
		$skipped = true;
	}
}

fin();
?>