<?php
	// Load Initialization File
	require_once '../core/init.php';

	// Load User
	$user = new User;

	// Check to see if User has Admin Permission
	if(!$user->hasPermission('admin')) {
		Session::flash('home', 'You must be an administrator to view that page');
		Redirect::to('../index.php');
	}
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Home - Admin</title>
	<!-- Load HTML Headers -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="../assets/css/admin.css">
</head>
<body>

	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-nav-demo" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="#" class="navbar-brand">Admin Page</a>
			</div>
			<ul class="nav navbar-nav navbar-left">
				<li><a href="<?php echo Config::get('links/app_root'); ?>">Home</a></li>

			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					if($user->isLoggedIn()) {
						if($user->hasPermission('admin')) {
							echo "<li><a href='#'>Users</a></li>";
						}

						echo "<li><a href='" . Config::get('links/app_root') . "profile.php?user={$user->data()->username}'>Profile</a></li>";
						echo "<li><a href='" . Config::get('links/app_root') . "logout.php'>Logout</a></li>";


					} else {

						echo "<li><a href='" . Config::get('links/app_root') . "register.php'>Sign Up</a></li>";
						echo "<li><a href='" . Config::get('links/app_root') . "login.php'>Login</a></li>";

					}
				?>
			</ul>
		</div>
	</nav>

	<?php
		if(Session::exists('admin')) {
			echo '<div class="container"><div class="alert alert-danger"><p>' . Session::flash('admin') . '</p></div></div>';
		}
	?>

	<div class="container">
		<div class="jumbotron">
			<h1>Admin Page</h1>
			<p>You have reached the admin page</p>
		</div>
	</div>

</body>
</html>