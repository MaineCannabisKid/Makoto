<?php
	// Include Core File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Grab User
	$user = new User;

	// Grab Instance of Google Client
	$googleClient = new Google_Client;
	// Load Google Auth
	$auth = new GoogleAuth($googleClient);



	// Check the Redirect Code from Google Authorization
	if($auth->checkRedirectCode()) { // if it passes do something

		// Grab the user information from Google Client
		$googleUser = $auth->getPayload();
		// Define the Google User's Email
		$email = $googleUser['email'];
		// Check if Google User is in our system
		if($user->find($email)) { // If user is found
			
			// Log the User In
			$login = $user->googleLogin($googleUser);

			// Check if login was sucessful
			if($login) {
				// Grab the Updated User Information
				$user = new User;
				// Flash A Message
				Session::flash('home', 'Login Successful! Enjoy your visit ' . $user->data()->name , 'success');
				// Redirect
				Redirect::to('index.php');
			} else {
				Session::flash('login', 'Login Failed, Please try again.', 'danger');
				Redirect::to('login.php');
			}

		} else { // User Not Found so...
			// Force user to register first
			// Flash the Message on the REGISTER PAGE and tell user to register in our system first
			Session::flash('register', 'You must register with our system first before loggin in with Google. Please fill out the following information, then click register.', 'warning');
			// Redirect to logout first to log the user out of the Google API System
			Redirect::to('logout.php');
			

		}


		echo '<br><br><pre>' , print_r($googleUser) , '</pre>';
		// Log the user into our system

		Session::flash('home', 'You were logged in successfully', 'success');
		// Redirect::to('index.php');
	}