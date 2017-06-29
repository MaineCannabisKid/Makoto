<?php 
	// Load Initialization File
	require_once '../../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'admin/usermanage/' . basename(__FILE__, '.php') . '.css';
	// Load User
	$user = new User;



?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - User Management - Makoto</title>
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
				<li class="active">User Management</li>
			</ol>	
		</div>
		
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
			<table class="table table-bordered table-hover">
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
						// Assign the correct table cell for corresponding role
						switch($user->groups) {
							case "3":
								$role = "<td class='danger'>Administrator</td>";
							break;
							case "2":
								$role = "<td class='success'>Moderator</td>";
							break;
							default:
								$role = "<td>User</td>";
							break;
						}
						// Echo out the rest of the table row
						echo "
							<tr>
								<td><a href='" . Config::get('links/app_root') . "profile.php?user={$user->id}'>@{$user->username}</a></td>
								<td>{$user->name}</td>
								<td>{$user->joined}</td>
								{$role}
								<td>
									<a href='deluser.php?id={$user->id}' class='btn btn-danger btn-xs hvr-float-shadow'><i class='fa fa-trash' aria-hidden='true'></i>&nbsp;&nbsp;Delete</a>
									<a href='edituser.php?id={$user->id}' class='btn btn-info btn-xs hvr-float-shadow'><i class='fa fa-pencil' aria-hidden='true'></i>&nbsp;&nbsp;Edit</a>
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