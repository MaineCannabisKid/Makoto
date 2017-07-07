<?php 
	// Load Initialization File
	require_once '../../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'admin/dbmanage/' . basename(__FILE__, '.php') . '.css';
	// Load the User
	$user = new User;

	// Is the user logged in
	if($user->isLoggedIn()) {
		// If the user does NOT have admin permission
		if(!$user->hasPermission(array('admin'))) {
			// User Not Logged In Redirect to Home Page
			Session::flash('home', 'You do not have permission to view that page', 'danger');
			Redirect::to('index.php');
		} else { // Authorization correct
			// Does input in the URL exist?
			if(Input::exists('get')) {
				// Grab the ID from the URL using GET
				$tableToDel = Input::get('tableName');
				switch($tableToDel) {
					case "":
					case null:
						$error404 = true;
					break;
					case "users":
					case "user_session":
					case "groups":
						Session::flash('admin-dbmanage', 'The table <strong>' . $tableToDel . '</strong> can not be deleted', 'danger');
						Redirect::to('admin/dbmanage');
					break;
				}

				// Get a new instance of the DB
				$_db = DB::getInstance();
				
				// Check and see if the table exists first, if it doesn't redirect
				if(!$_db->tableExists($tableToDel)) {
					Session::flash('admin-dbmanage', 'The table <strong>' . $tableToDel . '</strong> does not exist', 'danger');
					Redirect::to('admin/dbmanage');
				}



			} else { // Input does not exist
				$error404 = true;
			}

		}
	} else {
		// User Not Logged In Redirect to Home Page
		Session::flash('home', 'You are not logged in.', 'warning');
		Redirect::to('index.php');
	}

// Load Current User Again
$user = new User;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin - Delete Table<?php if(isset($tableToDel)) { echo ' @' . $tableToDel; } ?> - Makoto</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<!-- Navigation Bar -->
	<?php include(Config::get('file/navbar/default')); ?>
	<?php
		// Session Flash Message
		if(Session::exists('admin-user-del')) {
			echo Session::flash('admin-user-del');
		}

		// If 404 Error Occurs
		if(isset($error404)) {
			Redirect::to(404);
		}
	?>
	
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="<?php echo Config::get('links/app_root'); ?>">Home</a></li>
			<li><a href="../">Admin</a></li>
			<li><a href="./">Database Management</a></li>
			<li class="active">Delete Database</li>
		</ol>	
	</div>

	

	
	
	<div class="container">
		<div class="jumbotron">
			<h1>Delete Table '<?php echo $tableToDel; ?>'?</h1>
			<p>Please confirm that this is the table you would like to delete.</p>
			<p class="text-danger"><strong>All changes are final and are not recoverable</strong></p>
		</div>
	</div>

	<form action="deltableconfirm.php" method="post">
		<!-- Generate Token -->
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<!-- ID of User to Delete -->
		<input type="hidden" name="tableToDel" value="<?php echo $tableToDel; ?>">

		<div class="container">
			<div class="row">
				<div class="col-sm-6"><a href="./" class="btn btn-block btn-warning hvr-pop">Go Back</a></div>
				<div class="col-sm-6"><button class="btn btn-block btn-danger hvr-pop" type="submit">Delete</button></div>
			</div>
		</div>
	</form>

</body>
</html>