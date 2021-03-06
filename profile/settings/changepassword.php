<?php

	// Load Initialization File
	require_once '../../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'profile/settings/' . basename(__FILE__, '.php') . '.css';
	// Load User
	$user = new User;
	// Define error
	$error = false;
	// If user isn't logged in
	if(!$user->isLoggedIn()) {
		$error = true;
	}
	// If User Tried to Change Password
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {

			$validate = new Validate;
			$validation = $validate->check($_POST, array(
				'password_current' => array(
					'required' => true,
					'min' => 6
				),
				'password_new' => array(
					'required' => true,
					'min' => 6,
				),
				'password_new_again' => array(
					'required' => true,
					'min' => 6,
					'matches' => 'password_new'
				)
			));

			// Check if validation passed
			if($validation->passed()) {

				// Check if input password is as same as users current pass
				if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password) {
					Session::flash('password', 'Your current password is wrong. Please try again.', 'danger');
				} else {
					// Current password matches DB
					// Update User Info
					$salt = Hash::salt(32);
					$user->update(array(
						'password' => Hash::make(Input::get('password_new'), $salt),
						'salt' => $salt
					));

					// Session & Redirect
					Session::flash('iframe-home', 'Your password has been updated', 'success');
					Redirect::to('profile/settings/iframe-home.php');
				}




			} else { 
				// Define passwordErrors
				$passwordErrors = '<strong>Some errors occured when changing your password: </strong><br>';
				// Output Errors
				foreach($validation->errors() as $error) {
					// Add to passwordErrors, then display array as session flash on register
					$passwordErrors .= $error . "<br>";
				}
				Session::flash('password', $passwordErrors, 'danger');
			}


		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Change Password - Makoto</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	
		<?php
			// If there is an error grabbing the user
			if($error === true) {
				// Redirect to 404
				Redirect::to(404);
			}
			// Session Flash Message
			if(Session::exists('password')) {
				echo Session::flash('password');
			}
		?>
		
		<div class="container password-form">
			<h2>Change Password</h2>
			<form action="" method="post">

				<div class="form-group">
					<label for="password_current">Current Password</label>
					<input type="password" class="form-control" name="password_current" id="password_current" />
				</div>

				<div class="form-group">
					<label for="password_new">New Password</label>
					<input type="password" class="form-control" name="password_new" id="password_new" />
				</div>

				<div class="form-group">
					<label for="password_new_again">New Password Again</label>
					<input type="password" class="form-control" name="password_new_again" id="password_new_again" />
				</div>

				<button type="submit" class="btn btn-primary hvr-float-shadow">Change Password</button>
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />

			</form>

		</div>

</body>
</html>