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
					echo $userEditID = intval(Input::get('userEditID'));

					// Validate Input
					$validate = new Validate;
					$validation = $validate->check($_POST, array(
						'name' => array(
							'required' => true,
							'min' => 2,
							'max' => 50
						)
					));
					// If validation passed
					if($validation->passed()) {

						try {
							// Update the user
							$user->update(array(
								'name' => Input::get('name'),
								'groups' => Input::get('groups')
							), $userEditID);
							// Flash Message
							Session::flash('admin-user-manage', 'User information has been updated successfully.', 'success');
							Redirect::to('admin/usermanage.php');

						} catch(Exception $e) {
							die($e->getMessage());
						}

					} else {
						// Define registrationErrors
						$editErrors = '';
						// Output Errors
						foreach($validation->errors() as $error) {
							// Add to registrationErrors, then display array as session flash on register
							$editErrors .= $error . "<br>";
						}
						Session::flash('admin-user-edit', "<strong>Some errors occured when updating the user: </strong><br>" . $editErrors, 'danger');
						Redirect::to('admin/edituser.php?id=' . $userEditID);
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