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
	// Did User Change Name
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {

			$validate = new Validate;
			$validation = $validate->check($_POST, array(
				'name' => array(
					'required' => true,
					'min' => 2,
					'max' => 50
				)
			));
			if($validation->passed()) {

				try {
					// Update the user
					$user->update(array(
						'name' => Input::get('name')
					));
					// Flash Message
					Session::flash('home', 'Your details have been updated');
					Redirect::to('index.php');

				} catch(Exception $e) {
					die($e->getMessage());
				}
			} else {
				// Define registrationErrors
				$registrationErrors = '';
				// Output Errors
				foreach($validation->errors() as $error) {
					// Add to registrationErrors, then display array as session flash on register
					$registrationErrors .= $error . "<br>";
				}
				Session::flash('update', "<strong>Some errors occured when updating your Name: </strong><br>" . $registrationErrors);
			}


		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Change Name - OOP Login System</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<?php include(Config::get('file/navbar/default')); ?>

	
		<?php
			// Session Flash Message
			if(Session::exists('update')) {
				echo '<div class="container"><div class="alert alert-danger"><p>' . Session::flash('update') . '</p></div></div>';
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