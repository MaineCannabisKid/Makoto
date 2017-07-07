<?php
	// Include Core File
	require_once '../../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'profile/settings/' . basename(__FILE__, '.php') . '.css';
	// Grab User
	$user = new User;
	// Define error
	$error = false;
	// If user isn't logged in
	if(!$user->isLoggedIn()) {
		$error = true;
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>iFrame Settings Home - Makoto</title>
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
			if(Session::exists('iframe-home')) {
				echo Session::flash('iframe-home');
			}
		?>

		<div class="container splash">
			<h1>Settings</h1>
			<img style="width: 300px;" src="../../assets/imgs/profile/settings/iframe-icon.png">
			
		</div>

</body>
</html>