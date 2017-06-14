<?php

	// Load Initialization File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Load User
	$user = new User;
	// Check if User Is Logged In
	if(!$user->isLoggedIn()) {
		Session::flash('home', 'You must login first');
		Redirect::to('index.php');
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
					echo "Your current password is wrong. Please try again.";
				} else {
					// Current password matches DB
					// Update User Info
					$salt = Hash::salt(32);
					$user->update(array(
						'password' => Hash::make(Input::get('password_new'), $salt),
						'salt' => $salt
					));

					// Session & Redirect
					Session::flash('home', 'Your password has been updated');
					Redirect::to('index.php');
				}




			} else { 
				// Define registrationErrors
				$registrationErrors = '';
				// Output Errors
				foreach($validation->errors() as $error) {
					// Add to registrationErrors, then display array as session flash on register
					$registrationErrors .= $error . "<br>";
				}
				Session::flash('password', "<strong>Some errors occured when changing your password: </strong><br>" . $registrationErrors);
			}


		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Change Password - OOP Login System</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<?php include(Config::get('file/navbar/default')); ?>

	
		<?php
			// Session Flash Message
			if(Session::exists('password')) {
				echo '<div class="container"><div class="alert alert-danger"><p>' . Session::flash('password') . '</p></div></div>';
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

				<button type="submit" class="btn btn-default">Change Password</button>
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />

			</form>

		</div>

</body>
</html>