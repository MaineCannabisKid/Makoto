<?php
	require_once 'core/init.php';

	$user = new User();
	$user->logout();

	// Log the user out if register Session Flash Message Exists
	// This should only be set if the user is coming from "googleAuthRedirect.php"
	if(Session::exists('register')) {
		Redirect::to('register.php');
	} else { // Log out like normal
		Session::flash('home', 'You have logged out', 'info');
		Redirect::to('index.php');
	}

	

?>