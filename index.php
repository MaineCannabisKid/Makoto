<?php 
	// Load Initialization File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Load User
	$user = new User;


?>
<!DOCTYPE html>
<html>
<head>
	<title>Home - OOP Login System</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<?php include(Config::get('file/navbar/default')); ?>

	
		<?php
			// Session Flash Message
			if(Session::exists('home')) {
				echo '<div class="container"><div class="alert alert-danger"><p>' . Session::flash('home') . '</p></div></div>';
			}
		?>
		
		<div class="container"><div class="jumbotron"><h1>Home</h1><p>This is the App home page.</p></div></div>

</body>
</html>