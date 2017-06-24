<?php
	// Include Core File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Grab User
	$user = new User;

	// If user is logged in, no reason to go to the login page
	if(!$user->isLoggedIn()) {
		Session::flash('home', 'You need to login');
		Redirect::to('index.php');			
	}



?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile Settings - Makoto</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<?php include(Config::get('file/navbar/default')); ?>

	
		<?php
			// Session Flash Message
			if(Session::exists('settings')) {
				echo '<div class="container"><div class="alert alert-danger"><p>' . Session::flash('settings') . '</p></div></div>';
			}
		?>
		
		<div class="container"><div class="jumbotron"><h1>Settings</h1><p>What would you like to change?</p></div></div>

		<div class="container">
			<div class="row">

				<div class="col-sm-6">
					<img src="assets/imgs/icons/settings-id.png">
					<p>
						<a href="update.php">Change Your Name</a>
					</p>
				</div>

				<div class="col-sm-6">
					<img src="assets/imgs/icons/settings-lock.png">
					<p>
						<a href="changepassword.php">Change Password</a>
					</p>
				</div>

			</div>
		</div>

</body>
</html>