<?php 
	// Load Initialization File
	require_once 'core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . basename(__FILE__, '.php') . '.css';
	// Load User
	$user = new User;

	

	
	
	// Check to see if there are values in GET
	if(Input::exists('get')) {
		// GET Values
		
		// Table
		$t = Input::get('t');
		// Keywords
		$k = Input::get('k');
		
		// Instanciate the Search Class
		$search = new Search($t, $k);
		// Search
		$searchResults = $search->execute();
		

	} else {
		// Can't visit the page directly
		Session::flash('home', 'You can\'t visit this page directly.<br>You must visit the search page through the search bar.', 'danger');
		Redirect::to('index.php');
	}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Search - Makoto</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<?php include(Config::get('file/navbar/default')); ?>
	
	
	<?php
		// Session Flash Message
		if(Session::exists('search')) {
			echo Session::flash('search');
		}
	?>
	
	<!-- Enable Breadcrumbs If Not A Splash Page -->
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="./">Home</a></li>
			<li class="active">Search</li>
		</ol>	
	</div>

	<!-- Display Search Results -->
	<div class="container">
		<?php
		echo $searchResults;
		?>
	</div>

	<!-- Load the Search Javascript -->
	<script src="<?php echo Config::get('links/app_root'); ?>assets/js/search.js"></script>

</body>
</html>