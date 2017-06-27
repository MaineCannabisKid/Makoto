<?php
	// Include Core File
	require_once '../../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'profile/settings/' . basename(__FILE__, '.php') . '.css';
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

		<div class="container">
			<ol class="breadcrumb">
				<li><a href="<?php echo Config::get('links/app_root'); ?>">Home</a></li>
				<li><a href="<?php echo '../index.php?user=' . $user->data()->id; ?>">Profile</a></li>
				<li class="active">Settings</li>
			</ol>	
		</div>

		<?php
			// Session Flash Message
			if(Session::exists('settings')) {
				echo Session::flash('settings');
			}
		?>
		
		<!-- Container -->
		<div class="container settings-wrapper">
			<div class="row">
				<div class="col-sm-3">
					<div class="list-group">
						<a id="home" class="list-group-item active">
							Settings Home
						</a>
						<a id="password" class="list-group-item">Update Password</a>
						<a id="name" class="list-group-item">Change Name</a>
					</div>
				</div>
				<div class="col-sm-9 iframe-wrapper">
					<iframe src="iframe-home.php" id="settings-iframe" name="settings-iframe">
						
					</iframe>
				</div>
			</div>

		</div>

<script src="<?php echo Config::get('links/app_root') . 'assets/js/profile/settings/index.js'; ?>"></script>
</body>
</html>