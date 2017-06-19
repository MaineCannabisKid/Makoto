<?php
	// Load Initialization File
	require_once '../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'admin/' . basename(__FILE__, '.php') . '.css';
	// Load the User
	$user = new User;

	echo $field1type = Input::get('field1type') . "<br>";
	echo $field2type = Input::get('field2type') . "<br>";
	echo $field3type = Input::get('field3type') . "<br>";
	echo $field4type = Input::get('field4type') . "<br>";
?>