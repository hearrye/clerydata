<?php
if ($dbValidate){
	$db = new mysqli('localhost', 'hearrye', 'hearryehearrye', 'hearrye');
	if ($db->connect_errno > 0){
		die('Unable to connect to database ('.$db->connect_error.']');
	}
} else {
	die();
}
?>