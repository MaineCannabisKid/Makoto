<?php
	// Load Initialization File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Load User
	$user = new User;

	// If User Tried to Register
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'username' => array(
					'min' => 2,
					'max' => 20,
					'unique' => 'users',
					'numeric' => false
				),
				'email' => array(
					'min' => 6,
					'max' => 255,
					'unique' => 'users',
				),
				'password' => array(
					'min' => 6,
				),
				'password_again' => array(
					'matches' => 'password'
				),
				'name' => array(
					'min' => 2,
					'max' => 50
				),

			));

			if($validation->passed()) {
				$user = new User();

				$salt = Hash::salt(32);
				
				try {

					$user->create(array(
						'email' => Input::get('email'),
						'username' => Input::get('username'),
						'password' => Hash::make(Input::get('password'), $salt),
						'salt' => $salt,
						'name' => Input::get('name'),
						'joined' => date('Y-m-d H:i:s'),
						'groups' => 1
					));

					// Flash Message
					Session::flash('login', 'You have been registered and can now log in!', 'success');
					// Redirect User
					Redirect::to('login.php');

				} catch(Exception $e) {
					die($e->getMessage());
				}

			} else {
				// Define registrationErrors
				$registrationErrors = '<strong>Some errors occured when registering: </strong><br>';
				// Output Errors
				foreach($validation->errors() as $error) {
					// Add to registrationErrors, then display array as session flash on register
					$registrationErrors .= $error . "<br>";
				}
				Session::flash('register', $registrationErrors, 'danger');
			}
		}

	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Register - Makoto</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<?php include(Config::get('file/navbar/default')); ?>

	
		<?php
			// Session Flash Message
			if(Session::exists('register')) {
				echo Session::flash('register');
			}
		?>
		
		<div class="container register-form">
			<h2>Register</h2>
			<form action="" method="post">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" placeholder="Username" name="username" id="username" value="<?php  echo escape(Input::get('username')); ?>" autocomplete="off" required>
				</div>

				<div class="form-group">
					<label for="password">Choose a Password</label>
					<input type="password" placeholder="Choose a password..." class="form-control" name="password" id="password" required>
				</div>

				<div class="form-group">
					<label for="password_again">Enter your password again</label>
					<input type="password" placeholder="Enter the password again..." class="form-control" name="password_again" id="password_again" required>
				</div>
				
				<div class="form-group">
					<label for="email">Email</label>
					<p class="text-info">If you want to link your account with Google, enter your gMail address below. Once registered, login via the "Log in with Google" button.</p>
					<input type="email" placeholder="E-mail" class="form-control" name="email" id="email" required>
				</div>

				<div class="form-group">
					<label for="name">Your Name</label>
					<input type="text" placeholder="Your First Name" class="form-control" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>" required>
				</div>

				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<button type="submit" class="btn btn-default">Register</button>
			</form>

		</div>

</body>
</html>