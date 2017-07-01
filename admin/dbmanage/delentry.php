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
		if(!$user->hasPermission('admin')) {
			// User Not Logged In Redirect to Home Page
			Session::flash('home', 'You do not have permission to view that page', 'danger');
			Redirect::to('index.php');
		} else { // Authorization correct
			// Does input in the URL exist?
			if(Input::exists('get')) {

					// Grab Inputs
					$tableName = Input::get('tableName');
					// Define Variables
					$pageHTML = '';	
					$tableHeadHTML = '';
					// Get New DB
					$_db = DB::getInstance();

					// Check and see if table exists
					if($_db->tableExists($tableName)) { // Table exists
						
						// Check if id is valid
						$entryID = Input::get('id');
						if(!$_db->get($tableName, array('id', '=', $entryID))->count()) {
							Session::flash('admin-edit-table-entries', 'The id ' . $entryID . ' does not exist!', 'danger');
							Redirect::to("admin/dbmanage/edittableentries.php?tableName={$tableName}");
						}

						// Check if the form was submitted
						if(Input::exists('post') && Token::check(Input::get('token'))) {
							// Grab the entryID from the Form
							$entryIDConfirmed = Input::get('entryIDConfirmed');
							// Delte the Entry
							if($_db->delete($tableName, array('id', '=', $entryIDConfirmed))) { // Upon Successful Deletion
								// Redirect
								Session::flash('admin-edit-table-entries', 'The id ' . $entryIDConfirmed . ' has been deleted!', 'success');
								Redirect::to("admin/dbmanage/edittableentries.php?tableName={$tableName}");
							} else {
								// Something went wrong
								die("Something went wrong");
							}
						}


					} else {
						// Table doesn't exist
						$error404 = true;
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


?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin - Delete Entry - Makoto</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<!-- Navigation Bar -->
	<?php include(Config::get('file/navbar/default')); ?>

		<div class="container">
			<ol class="breadcrumb">
				<li><a href="<?php echo Config::get('links/app_root'); ?>">Home</a></li>
				<li><a href="../">Admin</a></li>
				<li><a href="./">Database Management</a></li>
				<li class="active">Delete Table Entry</li>
			</ol>	
		</div>
	
		<?php
			// Session Flash Message
			if(Session::exists('admin-del-table-entry')) {
				echo Session::flash('admin-del-table-entry');
			}

			// If 404 Error Occurs
			if(isset($error404)) {
				Redirect::to(404);
			}
		?>
		
		<div class="container">
			<div class="jumbotron">
				<h1>Delete Entry - ID: <?php if(isset($entryID)) { echo $entryID; } ?></h1>
				<p class="text-danger">Are you sure you want to delete?</p>
			</div>
		</div>



			<form action="" method="post">
			<!-- Generate Token -->
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<!-- The Table Name -->
			<input type="hidden" name="tableName" value="<?php echo $tableName; ?>">
			<!-- ID Of the Entry to delete -->
			<input type="hidden" name="entryIDConfirmed" value="<?php echo $entryID; ?>">

			<!-- Buttons -->
			<div class="container">
				<div class="row">
					<div class="col-sm-6"><a href="edittableentries.php?tableName=<?php echo $tableName; ?>" class="btn btn-block btn-warning hvr-pop">Go Back</a></div>
					<div class="col-sm-6"><button class="btn btn-block btn-danger hvr-pop" type="submit">Delete</button></div>
				</div>
			</div>
		</form>


</body>
</html>