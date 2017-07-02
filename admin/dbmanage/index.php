<?php 
	// Load Initialization File
	require_once '../../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'admin/dbmanage/' . basename(__FILE__, '.php') . '.css';
	// Load Current User
	$user = new User;

	// Is the user logged in
	if($user->isLoggedIn()) {
		// If the user does NOT have admin permission
		if(!$user->hasPermission(array('admin'))) {
			// User Not Logged In Redirect to Home Page
			Session::flash('home', 'You do not have permission to view that page', 'danger');
			Redirect::to('index.php');
		}
	} else {
		// User Not Logged In Redirect to Home Page
		Session::flash('home', 'You are not logged in.', 'warning');
		Redirect::to('index.php');
	}

	// Get a new instance of the DB
	$_db = DB::getInstance();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Database Management - Makoto</title>
	<!-- Load Head Contents -->
	<?php include(Config::get('file/head_contents')); ?>
	<!-- Page Specfic CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $cssFileName; ?>">
	</head>
<body>

	<?php include(Config::get('file/navbar/default')); ?>
		
		<div class="container">
			<ol class="breadcrumb">
				<li><a href="<?php echo Config::get('links/app_root'); ?>">Home</a></li>
				<li><a href="../">Admin</a></li>
				<li class="active">Database Management</li>
			</ol>	
		</div>
	
		<?php
			// Session Flash Message
			if(Session::exists('admin-dbmanage')) {
				echo Session::flash('admin-dbmanage');
			}
			if(Session::exists('admin-dbmanage2')) {
				echo Session::flash('admin-dbmanage2');
			}
		?>

		
		
		<div class="container">
			<div class="jumbotron">
				<h1>Database Management</h1>
				<p>This is the Database Management Page.</p>
				<p class="text-primary">On this page you can create, manage, and delete tables in your MySQL Database. This is a very simple version of PHPMyAdmin, and is not to replace PHPMyAdmin when creating complex databases.</p>
			</div>
		</div>

		<div class="container create-form">
			
			<form action="createtable.php" method="post" class="form-horizontal">
				<!-- Generate Token -->
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

				<div class="form-group">
					<label for="numFields" class="col-sm-2 control-label">Number of Columns/Fields</label>
					<div class="col-sm-10">
						<input class="form-control" id="numFields" placeholder="Numeric Values Only" name="numFields" type="number" required>
					</div>
				</div>
				<div class="form-group">
					<label for="tableName" class="col-sm-2 control-label">Table Name</label>
					<div class="col-sm-10">
						<input class="form-control" id="tableName" placeholder="Table Name" name="tableName" type="text" required>
					</div>
				</div>

				<div class="btn-wrapper">
					<button type="submit" class="btn btn-success hvr-grow-shadow">Create Table</button>
				</div>
			</form>

		</div>

		<div class="container">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Table Name (Protected)</th>
						<th>Number of Columns/Fields</th>
						<th>Number of Entries</th>
						<th>Action</th>
					</tr>
				</thead>
				
				<tbody>
					<?php

					

					// Grab the array of Tables
					$tables = $_db->getTables();

					// Loop through the array and grab all the table objects
					foreach($tables as $table) {
						// Loop through the object and grab the table name
						foreach($table as $name) {
							// Get Number of columns/fields in table
							$numFields = $_db->getFieldCount($name);

							$numEntries = $_db->getEntriesCount($name);

							switch($name) {
								case "user_session":
								case "groups":
									// Enable these lines if you want groups and user_session visible
									
									// echo "
									// 	<tr>
									// 		<td class='text-muted'>{$name} (P)</td>
									// 		<td class='text-muted'>Protected</td>
									// 		<td class='text-muted'>Protected</td>
									// 		<td class='text-danger'>Can Not Edit</td>
									// 	</tr>
									// ";
								break;
								case "users":
									echo "
										<tr>
											<td class='text-muted'>{$name}</td>
											<td class='text-muted'>Protected</td>
											<td class='text-muted'>{$numEntries}</td>
											<td class='text-danger'><a href='usermanage.php' class='btn btn-info btn-xs hvr-float-shadow'><i class='fa fa-users' aria-hidden='true'></i> User Management</a></td>
										</tr>
									";
								break;

								default:
									echo "
										<tr>
											<td>{$name}</td>
											<td>{$numFields}</td>
											<td>{$numEntries}</td>
											<td>
												<a href='edittablefields.php?tableName={$name}' class='btn btn-info btn-xs hvr-float-shadow'><i class='fa fa-pencil' aria-hidden='true'></i>&nbsp;&nbsp;Edit Fields</a>
												<a href='edittableentries.php?tableName={$name}' class='btn btn-info btn-xs hvr-float-shadow'><i class='fa fa-pencil' aria-hidden='true'></i>&nbsp;&nbsp;Edit Entries</a>
												<a href='deltable.php?tableName={$name}' class='btn btn-danger btn-xs hvr-float-shadow'><i class='fa fa-trash' aria-hidden='true'></i></a>
											</td>
										</tr>
									";
								break;
							}

						}
					}

					?>

					
				</tbody>

			</table>
		</div>
		
</body>
</html>