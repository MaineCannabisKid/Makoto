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
						<th>Action</th>
					</tr>
				</thead>
				
				<tbody>
				
					<tr>
						<td><a href="#">@username</a></td>
						<td>Name Test</td>
						<td>2017-09-06 11:33:11</td>
						<td>
							<a href="#"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
							<a href="#"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Ban&nbsp;&nbsp;</a>
							<a href="#"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;Edit</a>
						</td>
					</tr>
					
					<tr>
						<td><a href="#">@username</a></td>
						<td>Name Test</td>
						<td>2017-09-06 11:33:11</td>
						<td>
							<a href="#"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
							<a href="#"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Ban&nbsp;&nbsp;</a>
							<a href="#"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;Edit</a>
						</td>
					</tr>

					<tr>
						<td><a href="#">@username</a></td>
						<td>Name Test</td>
						<td>2017-09-06 11:33:11</td>
						<td>
							<a href="#"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
							<a href="#"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Ban&nbsp;&nbsp;</a>
							<a href="#"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;Edit</a>
						</td>
					</tr>

					<tr>
						<td><a href="#">@username</a></td>
						<td>Name Test</td>
						<td>2017-09-06 11:33:11</td>
						<td>
							<a href="#"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
							<a href="#"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Ban&nbsp;&nbsp;</a>
							<a href="#"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;Edit</a>
						</td>
					</tr>
					

				</tbody>


			</table>
		</div>

</body>
</html>