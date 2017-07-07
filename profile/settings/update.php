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

	// Did User Change Name
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {

			// Grab the Validate Class
			$validate = new Validate;
			// Check if the input's validate ok or not
			// Basically checking the Inputs against the values
			$validation = $validate->check($_POST, array(
				'name' => array(
					'required' => true,
					'min' => 2,
					'max' => 50
				)
			));
			// If validation passed
			if($validation->passed()) {
				// Do a try catch block to catch any errors from the PDO handler
				try {
					// Update the user
					$user->update(array(
						'name' => Input::get('name')
					));

					// Flash Message
					Session::flash('iframe-home', 'Your details have been updated', 'success');
					Redirect::to('profile/settings/iframe-home.php');


				} catch(Exception $e) { // if something went wrong
					// Die and echo message
					die($e->getMessage());
				}
			} else { // Validation didn't pass

				// Define updateErrors as empty string
				$updateErrors = '';

				// Loop through the errors as error
				foreach($validation->errors() as $error) {
					// Add error to updateErrors
					$updateErrors .= $error . "<br>";
				}
				// Session Flash message
				// No redirect necessary as this is the 'update' page
				Session::flash('update', "<strong>Some errors occured when updating your Name: </strong><br>" . $updateErrors, 'danger');
			}


		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Change Name - Makoto</title>
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
			// Display the Flash Message
			if(Session::exists('update')) {
				echo Session::flash('update');
			}
		?>
		
		<div class="container update-form">
			<h2>Change Name</h2>
			<form action="" method="post">
				<div class="form-group">
					<label for="name">Name: </label>
					<input type="text" class="form-control" name="name" id="name" value="<?php echo escape($user->data()->name); ?>">
				</div>

				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<button type="submit" class="btn btn-primary hvr-float-shadow">Change Name</button>
			</form>

		</div>

</body>
</html>