<?php 
	// Load Initialization File
	require_once '../../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'admin/usermanage/' . basename(__FILE__, '.php') . '.css';
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
				$userEditID = intval(Input::get('id'));
				// Check if userEditID is null/empty
				if($userEditID == "" || $userEditID == null) {
					$error404 = true;
				}
				// Grab User from DB to Edit
				$userToEdit = new User($userEditID);

				// If the user doesn't exist 404
				if(!$userToEdit->exists()) {
					$error404 = true;
				} 

				// Store Data in userToEdit
				$userToEdit = $userToEdit->data();
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
	<title>Admin - Edit User<?php if(isset($userToEdit->username)) { echo ' @' . $userToEdit->username; } ?> - Makoto</title>
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
		if(Session::exists('admin-user-edit')) {
			echo Session::flash('admin-user-edit');
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
			<li><a href="./">User Management</a></li>
			<li class="active">Edit User</li>
		</ol>	
	</div>


	<div class="container">
		<div class="jumbotron">
			<h1>Editing <a target="_blank" href="<?php echo Config::get('links/app_root');?>profile.php?user=<?php echo $userToEdit->username; ?>">@<?php echo $userToEdit->username; ?></a></h1>
			<p>Please make the changes you wish to make, then click save.</p>
		</div>
	</div>


	<div class="container form">
		<form action="edituserconfirm.php" method="post" class="form-horizontal">
			<!-- Generate Token -->
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<!-- ID of User to Delete -->
			<input type="hidden" name="userEditID" value="<?php echo $userEditID; ?>">

			<!-- Username -->
			<div class="form-group">
				<label class="col-sm-2 control-label">Username</label>
				<div class="col-sm-10">
				<p class="form-control-static"><?php echo $userToEdit->username; ?></p>
				</div>
			</div>

			<!-- Name -->
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
				<input type="text" class="form-control" id="name" name="name" value="<?php echo $userToEdit->name; ?>">
				</div>
			</div>

			<!-- Role Selector -->
			<div class="form-group">
				<label for="groups" class="col-sm-2 control-label">Role</label>
				<div class="col-sm-10">
					<select class="form-control" name="groups">
						<?php
							echo $userToEdit->groups;
							// Display Correct Group in Select Dropdown
							switch($userToEdit->groups) {
								case '1': // Standard User
									echo '
								 		<option value="3">Administrator</option>
								 		<option value="2">Moderator</option>
								 		<option  value="1" selected>Standard User</option>
									';
								break;
								case '2': // Moderator
									echo '
								 		<option value="3">Administrator</option>
								 		<option value="2" selected>Moderator</option>
								 		<option  value="1">Standard User</option>
									';
								break;
								case '3': // Administrator
									echo '
								 		<option value="3" selected>Administrator</option>
								 		<option value="2">Moderator</option>
								 		<option  value="1">Standard User</option>
									';
								break;
								default: // Error out if something went wrong
									echo '<option>Something went wrong</option>';
								break;
							}
						?>
					</select>
				</div>
			</div>

			<!-- Submit & Go Back Buttons -->
			<div class="row">
				<div class="col-sm-6"><a href="./" class="btn btn-block btn-warning hvr-pop">Go Back</a></div>
				<div class="col-sm-6"><button class="btn btn-block btn-success hvr-pop" type="submit">Save</button></div>
			</div>
		

		</form>
	</div>

</body>
</html>