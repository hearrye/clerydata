<?php
if ($dbValidate){
  $settings = parse_ini_file('app.ini', true)['db'];
  $db = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['name']);
	if ($db->connect_errno > 0){
		die('Unable to connect to database ('.$db->connect_error.']');
	}
} else {
	die();
}
?>