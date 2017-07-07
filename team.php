<?php
	// Include Core File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Grab User
	$user = new User;
	// Grab the DB Instance
	$_db = DB::getInstance();

	// Grab all the users
	$usersArr = $_db->getAll('users');


?>

<!DOCTYPE html>
<html>
<head>
	<title>Users - Makoto</title>
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
		if(Session::exists('users')) {
			echo Session::flash('users');
		}
	?>
	
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="<?php echo Config::get('links/app_root'); ?>">Home</a></li>
			<li class="active">The Team</li>
		</ol>	
	</div>

	
	<?php
	$adminUsers = array();
	$modUsers = array();

	// Loop through the users and assign to role array
	foreach($usersArr as $singleUser) {
		$role = $singleUser->groups;
		switch($role) {
			case '3':
				array_push($adminUsers, $singleUser);
			break;	
			case '2':
				array_push($modUsers, $singleUser);
			break;
		}
	}

	?>

	<!-- Administrators -->
	<div class="container users-wrapper">
		<h1>Administrators</h1>
		<hr />
		<?php
			if(!$adminUsers) {
				echo "<h4>No users in this role.</h4>";
			} else {
				foreach($adminUsers as $admin) {
					$picture = $admin->picture;
					$name = $admin->name;
					$id = $admin->id;

					if(!$picture) {
						$picture = Config::get('links/app_root') . 'assets/imgs/profile/default.png';
					}
					echo "	<div class='media'>
								<div class='media-left'>
									<a href='profile.php?user={$id}'>
										<img class='media-object' src='{$picture}' alt='{$name}'>
									</a>
								</div>
								<div class='media-body media-middle'>
									<h4 class='media-heading'>{$name}</h4>
									Bio will go here (first 100 characters)
								</div>
							</div>
						";
				}
			}
		?>
	</div>

	<!-- Moderators -->
	<div class="container users-wrapper">
		<h1>Moderators</h1>
		<hr />
		<?php
			if(!$modUsers) {
				echo "<h4>No users in this role.</h4>";
			} else {
				foreach($modUsers as $mod) {
					$picture = $mod->picture;
					$name = $mod->name;
					$id = $mod->id;

					// Check to see if picture is null
					if(!$picture) {
						$picture = Config::get('links/app_root') . 'assets/imgs/profile/default.png';
					}
					echo "	<div class='media'>
								<div class='media-left'>
									<a href='profile.php?user={$id}'>
										<img class='media-object' src='{$picture}' alt='{$name}'>
									</a>
								</div>
								<div class='media-body media-middle'>
									<h4 class='media-heading'>{$name}</h4>
									Bio will go here (first 100 characters)
								</div>
							</div>
						";
				}
			}
		?>
	</div>


	



</body>
</html>