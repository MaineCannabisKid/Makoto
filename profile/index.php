<?php
	// Require Initialization File
	require_once '../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'profile/' . basename(__FILE__, '.php') . '.css';

	// Define error
	$error = false;

	// If $_GET['user'] is null
	if(!$username = Input::get('user')) {
		$error = true;
	} else {
		// Grab user based off $_GET['user'] as $username
		$user = new User($username);
		// If user does not exist in DB
		if(!$user->exists()) {
			$error = true;
		} else {
			// Figure out what group user is apart of
			$group = null;
			if($user->hasPermission(array('admin'))) {
				$group = '<span style="color: rgba(192, 57, 43,1.0); font-size: .5em">(Admin) </span>';
			} else if($user->hasPermission(array('moderator'))) {
				$group = '<span style="color: rgba(39, 174, 96,1.0); font-size: .5em">(Moderator) </span>';
			} else {
				$group = '<span style="color: rgba(119, 119, 119,1.0); font-size: .5em">';
				$group .= $user->data()->name;
				$group .= '</span>';
			}
			// Grab user data and assign to variable for easier access
			$data = $user->data();
			// *******************************************
			// Do NOT forget to escape() all data values!
			// escape($data->value)
			// *******************************************
			// Defining User Data Variables Below
			// *******************************************
			
			// If the profile image is there
			if(isset($data->picture)) {
				// Display Profile Image
				$picture = "<img class='profile-img' src='{$data->picture}'>";
			} else {
				// Display Default Profile Image
				$picture = "<img class='profile-img' src='" . Config::get('links/app_root') . "assets/imgs/profile/default.png'>";
			}
		}
	}

	// Grab current user to display things correctly
	$user = new User;
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile <?php echo (isset($data->username)) ? 'of ' . $data->username : 'Page'; ?> - Makoto</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<!-- Navbar -->
	<?php include(Config::get('file/navbar/default')); ?>
	
	<?php
		// If there is an error grabbing the user
		if($error === true) {
			// Redirect to 404
			Redirect::to(404);
		}

		// Session Flash Message
		if(Session::exists('profile')) {
			echo Session::flash('profile');
		}
	?>
	
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="<?php echo Config::get('links/app_root'); ?>">Home</a></li>
			<li class="active">Profile</li>
		</ol>	
	</div>
	

	
	

	<div class="container">
		<div class="jumbotron">
			<h1><?php echo $picture; ?> @<?php echo escape($data->username); ?> <?php echo $group; ?></h1>
			<p>Bio will go here</p>
			
		</div>
	</div>

	

	
</body>
</html>
