<?php

	// Load Initialization File
	require_once '../../core/init.php';
	// Load the Current User
	$user = new User;

	// Is the user logged in
	if($user->isLoggedIn()) {
		// If the user does NOT have admin permission
		if(!$user->hasPermission('admin')) {
			// User Not Logged In Redirect to Home Page
			Session::flash('home', 'You do not have permission to view that page', 'danger');
			Redirect::to('index.php');
		} else {
			// Authorization correct
			if(Input::exists()) {
				if(Token::check(Input::get('token'))) {
				
					// Grab the ID from the URL using GET
					echo $userDelID = intval(Input::get('idToDel'));

					// Delete User From Database
					if($user->delete($userDelID)) {
						Session::flash('admin-user-manage', 'Deleted User without any problems', 'success');
						Redirect::to('admin/usermanage');
					} else { // If Unsuccessful, then display error message
						Session::flash('admin-user-manage', 'Something went wrong', 'danger');
						Redirect::to('admin/usermanage');
					}

				}
			}
			


		}
	} else {
		// User Not Logged In Redirect to Home Page
		Session::flash('home', 'You are not logged in.', 'warning');
		Redirect::to('index.php');
	}

?>