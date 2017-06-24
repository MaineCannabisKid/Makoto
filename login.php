<?php
	// Include Core File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Grab User
	$user = new User;
	// Grab Instance of Google Client
	$googleClient = new Google_Client;
	// Load Google Auth
	$auth = new GoogleAuth($googleClient);

	// If user is logged in, no reason to go to the login page
	if($user->isLoggedIn()) {
		Session::flash('home', 'You are already logged in', 'warning');
		Redirect::to('index.php');			
	}

	
	// If form was submitted do this
	if(Input::exists()) {
		// If token check is valid
		if(Token::check(Input::get('token'))) {
			
			// Log User In
			$user = new User();
			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('email'), Input::get('password'), $remember);

			// Check if login was sucessful
			if($login) {
				Redirect::to('index.php');
			} else {
				Session::flash('login', 'Login Failed, Please try again.', 'danger');
				Redirect::to('login.php');
			}

		}
	}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Login - Makoto</title>
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
			echo Session::flash('login');
		}
	?>

	<div class="container">

		<form method="post" action="">
			<div class="container login-form">
				<h2>Login</h2>
				<form>
					<div class="form-group">
						<label for="email">E-Mail</label>
						<input type="email" class="form-control" name="email" id="email" placeholder="E-Mail" autocomplete="off" required>
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
					<button type="submit" class="btn btn-default hvr-float-shadow">Log In</button>
					<a href="<?php echo $auth->getAuthUrl(); ?>" class="hvr-float-shadow">
						<img src="assets/imgs/buttons/login-google.png" class="login-btn">
					</a>
				</form>
			</div>
		</form>

	</div>



</body>
</html>