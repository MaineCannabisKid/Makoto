<?php

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
		),
	);

// Define Variables for Production vs. Development
if($_SERVER['HTTP_HOST'] == 'localhost') { // Development Mode
	

	// // Set INI to log errors
	// ini_set('log_errors', 1);
	// // Set Error Log Path Variable (Grabs the Root Directory of the Project, then appends on the path)
	// $errorPath = dirname(substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT']))) . '/logs/php/error.log';
	// var_dump($errorPath);
	// // Set INI for error_log path
	// ini_set('error_log', $errorPath);
	// // Give some code thats going to throw an error
	// $db = new PDO("mysql:host=a;dbname=test", "root", "");


	// Set the MySQL Connection Variables
	$GLOBALS['config'] += array(
		'mysql' => array(
			'host' => 'localhost',
			'username' => 'root',
			'password' => '',
			'db' => 'Makoto'
		)
	);
} else { // Production Mode
	
	// Set MySQL Connection Variables
	$GLOBALS['config'] += array(
		'mysql' => array(
			'host' => '************',
			'username' => '************',
			'password' => '************',
			'db' => 'Makoto'
		)
	);
}

//-----------------------------//
// DO NOT EDIT BELOW THIS LINE //
//-----------------------------//

// Start a Session
session_start();

// Autoload Vendor Folder
require_once dirname(__FILE__) . '/vendor/autoload.php';

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

// Call a new PHPMailer Object
$mail = new PHPMailer;


?>