<?php
if ($dbValidate){
	$db = new mysqli('mysql09.powweb.com', 'hearrye', 'hearryehearrye', 'hearrye');
	if ($db->connect_errno > 0){
		die('Unable to connect to database ('.$db->connect_error.']');
	}
} else {
	die();
}
?>