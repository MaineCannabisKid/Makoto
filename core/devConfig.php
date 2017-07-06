<?php

// Set the MySQL Connection Variables
$GLOBALS['config'] += array(
	'mysql' => array(
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'db' => 'Makoto'
	),
	'links' => array(
		// Application Root Folder
		'app_root' =>  dirname(substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT']))) . '/',
		'css_root' =>  dirname(substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT']))) . '/assets/css/'
	),
	'google' => array(
		'redirectURI' => 'http://localhost/Makoto/googleAuthRedirect.php'
	)
);