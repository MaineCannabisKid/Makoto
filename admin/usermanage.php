<?php 
	// Load Initialization File
	require_once '../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'admin/' . basename(__FILE__, '.php') . '.css';
	// Load User
	$user = new User;


?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - User Management - OOP Login System</title>
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
			if(Session::exists('admin-user-manage')) {
				echo Session::flash('admin-user-manage');
			}
		?>
		
		<div class="container">
			<div class="jumbotron">
				<h1>User Management</h1>
				<p>This is the User Management Page. Please select a user, and decide what to do.</p>
			</div>
		</div>

		<div class="container">
			<table class="table table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Username</th>
						<th>Name</th>
						<th>Date Joined</th>
						<th>Role</th>
						<th>Action</th>
					</tr>
				</thead>
				
				<tbody>
				
					
					<?php

					// Get a New Database Instance
					$_db = DB::getInstance();
					// Grab all users
					$users = $_db->getAll('users');


					// Echo Out all the Users
					foreach($users as $user) {
						switch($user->groups) {
							case "3":
								echo "<tr class='danger'>";
								$role = "Administrator";
							break;
							case "2":
								echo "<tr class='success'>";
								$role = "Moderator";
							break;
							default:
								echo "<tr>";
								$role = "User";
							break;
						}
						echo "
								<td><a href='" . Config::get('links/app_root') . "profile.php?user={$user->id}'>@{$user->username}</a></td>
								<td>{$user->name}</td>
								<td>{$user->joined}</td>
								<td>{$role}</td>
								<td>
									<a href='deluser.php?user={$user->id}' class='btn btn-danger btn-xs' type='submit'><i class='fa fa-trash' aria-hidden='true'></i>&nbsp;&nbsp;Delete</a>
									<a href='edituser.php?user={$user->id}' class='btn btn-info btn-xs' type='submit'><i class='fa fa-pencil' aria-hidden='true'></i>&nbsp;&nbsp;Edit</a>
								</td>
							</tr>
						";
					}


					?>
					

				</tbody>


			</table>
		</div>

</body>
</html>