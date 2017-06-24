<?php
	// Load Initialization File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Load User
	$user = new User;
	// Check if User Is Logged In
	if(!$user->isLoggedIn()) {
		Session::flash('home', 'You must login first!', 'danger');
		Redirect::to('index.php');
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
					// Flash Message stores a message in a SESSION and then
					// displays it on the 'home' screen which is index.php
					// 'success' is the color of alert to give user
					Session::flash('home', 'Your details have been updated', 'success');
					// Redirect to index.php
					// Once there Session::flash() will display the message
					Redirect::to('index.php');

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
				Session::flash('update', "<strong>Some errors occured when updating your Name: </strong><br>" . $updateErrors);
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

	<?php include(Config::get('file/navbar/default')); ?>

	
		<?php
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
				<button type="submit" class="btn btn-default">Change Name</button>
			</form>

		</div>

</body>
</html>