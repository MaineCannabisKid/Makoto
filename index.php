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
	<title>Home - Makoto</title>
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
				echo Session::flash('home');
			}
		?>

		<!-- Enable Breadcrumbs If Not A Splash Page -->
		<!-- <div class="container">
			<ol class="breadcrumb">
				<li class="active">Home</li>
			</ol>	
		</div> -->


		<div class="splash-wrapper">
			<p class="jpn-char">真</p>
			<p class="title">Makoto</p>
		</div>

</body>
</html>