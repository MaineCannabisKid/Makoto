<?php

// Start a Session
session_start();

// Create a Config Variable
$GLOBALS['config'] = 
	array(
		'remember' => array(
			'cookie_name' => 'hash',
			'cookie_expiry' => 604800 // 1 Month
		),
		'session' => array(
			'session_name' => 'user',
			'token_name' => 'token'
		),
		'file' => array(
			// This will include all the things such as CSS and jQuery CDN links
			'head_contents' => dirname(__FILE__) . '/includes/head/headContents.php',
			'navbar' => array(
				'default' => dirname(__FILE__) . '/includes/navbar/default.php',
				'admin' => dirname(__FILE__) . '/includes/navbar/admin.php'
			)
		),
		'links' => array(
			// Application Root Folder
			'app_root' =>  dirname(substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT']))) . '/',
			'css_root' =>  dirname(substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT']))) . '/assets/css/'
		)
	);

// Define Which Host to Connect To
if($_SERVER['HTTP_HOST'] == 'localhost') { // If website is in Development Mode, Use localhost
	$GLOBALS['config'] += array(
		'mysql' => array(
			'host' => 'localhost',
			'username' => 'root',
			'password' => '',
			'db' => 'modelawiki'
		)
	);
} else { // If Website is Live, Use Network Solutions Database
	$GLOBALS['config'] += array(
		'mysql' => array(
			'host' => 'mysqlcluster20',
			'username' => 'modelawiki',
			'password' => 'Jonathan7829',
			'db' => 'modelawiki'
		)
	);
}


// Auto Load Classes
spl_autoload_register(function($class) {
	require_once dirname(__FILE__) . "/classes/" . $class . '.php';
});

// Load Functions
require_once dirname(__FILE__) . '/functions/sanitize.php';

// Define Variables
$cookieName = Config::get('remember/cookie_name');
$sessionName = Config::get('session/session_name');
// Check to see if User is Logged in by "remember me" functionality on the Login Page
if(Cookie::exists($cookieName) && !Session::exists($sessionName)) {

	$hash = Cookie::get($cookieName);
	$hashCheck = DB::getInstance()->get('user_session', array('hash', '=', $hash));

	// If entry is in the database, user is logged in
	if($hashCheck->count()) {
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}


}

?>