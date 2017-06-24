<?php 
	// Load Initialization File
	require_once '../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'admin/' . basename(__FILE__, '.php') . '.css';
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
				// Grab the ID from the URL using GET
				$userDelID = intval(Input::get('id'));
				if($userDelID == "" || $userDelID == null) {
					$error404 = true;
				}
				// Grab User from DB to Delete
				$userToDel = new User($userDelID);
				// If the user doesn't exist 404
				if(!$userToDel->exists()) {
					$error404 = true;
				} 
				// Store data in userToDel
				$userToDel = $userToDel->data();
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
	<title>Admin - Delete User<?php if(isset($userToDel->username)) { echo ' @' . $userToDel->username; } ?> - Makoto</title>
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
			<div class="jumbotron">
				<h1>Delete <a target="_blank" href="<?php echo Config::get('links/app_root');?>profile.php?user=<?php echo $userToDel->username; ?>">@<?php echo $userToDel->username; ?></a>?</h1>
				<p>Please confirm that this is the user you would like to delete</p>
			</div>
		</div>

		<form action="deluserconfirm.php" method="post">
			<!-- Generate Token -->
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<!-- ID of User to Delete -->
			<input type="hidden" name="idToDel" value="<?php echo $userDelID; ?>">

			<div class="container">
				<div class="row">
					<div class="col-sm-6"><a href="usermanage.php" class="btn btn-block btn-warning hvr-pop">Go Back</a></div>
					<div class="col-sm-6"><button class="btn btn-block btn-danger hvr-pop" type="submit">Delete</button></div>
				</div>
			</div>
		</form>

</body>
</html>