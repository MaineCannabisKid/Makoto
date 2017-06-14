<?php
	// Include Core File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Grab User
	$user = new User;

	// If user is logged in, no reason to go to the login page
	if($user->isLoggedIn()) {
		Session::flash('home', 'You are already logged in');
		Redirect::to('index.php');			
	}

	// If form was submitted do this
	if(Input::exists()) {
		// If token check is valid
		if(Token::check(Input::get('token'))) {
			
			// Log User In
			$user = new User();
			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			// $login = true;


			// Check if login was sucessful
			if($login) {
				Redirect::to('index.php');
			} else {
				Session::flash('login', 'Login Failed, Please try again.');
				Redirect::to('login.php');
			}

		}
	}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Login - OOP Login System</title>
	<!-- Load HTML Headers -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
</head>
<body>

	<!-- Navbar -->
	<?php include(Config::get('file/navbar/default')); ?>

	<?php
		// Session Flash Message
		if(Session::exists('login')) {
			echo '<div class="container"><div class="alert alert-danger"><p>' . Session::flash('login') . '</p></div></div>';
		}
	?>

	<div class="container">

		<form method="post" action="">
			<div class="container login-form">
				<h2>Login</h2>
				<form>
					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" class="form-control" name="username" id="username" placeholder="Username" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label for="remember">
							<input type="checkbox" name="remember" id="remember"> Remember Me
						</label>
					</div>
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</form>



	</div>


</body>
</html>