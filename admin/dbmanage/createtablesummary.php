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
		} else {
			// Authorization correct
			if(Input::exists()) {
				if(Token::check(Input::get('token'))) {
				
					// Grab Number of Fields
					$numFields = Input::get('numFields');
					// Grab Date and Time Field
					$dateTime = Input::get('dateTime');
					// Grab Table Name
					$tableName = Input::get('tableName');

					// Decide what HTML to display for $dateTime
					if($dateTime == "true") {
						$dateTimeHTML = '
							<div class="form-group">
								<label for="tableName" class="col-sm-2 control-label">Date/Time</label>
								<div class="col-sm-10">
									<label class="radio-inline">
										<input type="radio" name="dateTime" value="true" checked> <span class="text-info">Add a Date/Time field</span>
									</label>
									<label class="radio-inline">
										<input type="radio" name="dateTime" value="false" disabled> <span class="text-muted">Don\'t add a Date/Time field</span>
									</label>
								</div>
							</div>
						';
					} else {
						$dateTimeHTML = '
							<div class="form-group">
								<label for="tableName" class="col-sm-2 control-label">Date/Time</label>
								<div class="col-sm-10">
									<label class="radio-inline">
										<input type="radio" name="dateTime" value="true" disabled> <span class="text-muted">Add a Date/Time field</span>
									</label>
									<label class="radio-inline">
										<input type="radio" name="dateTime" value="false" checked> <span class="text-danger">Don\'t add a Date/Time field</span>
									</label>
								</div>
							</div>
						';
					}

					// Dynamically generate number of fields and type
					$fieldHTML = '';
					for($i = 1; $i <= $numFields; $i++) {
						
						$fieldName = Input::get("field" . $i . "name");
						// Blacklist words as field name
						switch($fieldName) {

							case "id":
							case "desc":
							case "datetime":
								Session::flash('admin-dbmanage', 'You can not use the field name \'' . $fieldName . '\' when creating your table.', 'warning');
								Redirect::to('admin/dbmanage');
							break;

						}

						$fieldType = Input::get("field" . $i . "type");

						switch($fieldType) {
							case "text":
								$fieldTypeHTML = "
									<input type='hidden' name='field{$i}type' value='text'>
									<select class='form-control' disabled required>
										<option title='Holds a string with a maximum length of 65,535 characters' selected>Text</option>
									</select>
								";
							break;
							case "int":
								$fieldTypeHTML = "
									<input type='hidden' name='field{$i}type' value='int'>
									<select class='form-control' disabled required>
										<option title='-2147483648 to 2147483647 normal. 0 to 4294967295 UNSIGNED*.'>Integer</option>
									</select>
								";
							break;
							case "varchar32":
								$fieldTypeHTML = "
									<input type='hidden' name='field{$i}type' value='varchar32'>
									<select class='form-control' disabled required>
										<option title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (32)</option>
									</select>
								";
							break;
							case "varchar64":
								$fieldTypeHTML = "
									<input type='hidden' name='field{$i}type' value='varchar64'>
									<select class='form-control' disabled required>
										<option title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (64)</option>
									</select>
								";
							break;
							case "varchar128":
								$fieldTypeHTML = "
									<input type='hidden' name='field{$i}type' value='varchar128'>
									<select class='form-control' disabled required>
										<option title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (128)</option>
									</select>
								";
							break;
							case "varchar255":
								$fieldTypeHTML = "
									<input type='hidden' name='field{$i}type' value='varchar255'>
									<select class='form-control' disabled required>
										<option title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (255)</option>
									</select>
								";
							break;
							
						}
						
						$fieldHTML .= "

							<div class='form-group'>
								<label for='field{$i}' class='col-sm-2 control-label'>Field {$i}</label>
								<div class='col-sm-10'>
									<div class='col-sm-6'>
										<input type='text' class='form-control' id='field{$i}' name='field{$i}name' value='" . $fieldName . "' pattern='^\S+$' title='No Spaces are allowed' readonly required>
									</div>
									<div class='col-sm-6'>
										" . $fieldTypeHTML . "
									</div>
								</div>
							</div>

						";

					}



				} else {
					Session::flash('admin-dbmanage', 'Something went wrong when creating the table <strong>' . $tableName . '</strong>. Please try again. <strong>Error Code:</strong> createtableconfirm', 'danger');
					Session::flash('admin-dbmanage2', 'We have detected that you might have refreshed the page. Thats not allowed. Please use the \'Go Back\' button instead.');
					Redirect::to('admin/dbmanage');
				}
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
	<title>Admin - Create Table Summary - Makoto</title>
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
		if(Session::exists('admin-create-table')) {
			echo Session::flash('admin-create-table');
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
			<li class="active">Create Table</li>
		</ol>	
	</div>
	
	

	
	
	<div class="container">
		<div class="jumbotron bg-primary">
			<h1>'<?php if(isset($tableName)) { echo $tableName; } ?>' Table Summary</h1>
			<p>Please confirm the information below. Once you're done so, please click "Create Table"</p>
		</div>
	</div>


	<div class="container form-wrapper">
		<h3>Fields & Values</h3>
		<form class="form-horizontal" action="createtableconfirm.php" method="post">
			<!-- Generate Token -->
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<!-- number of fields wanted in the table -->
			<input type="hidden" name="numFields" value="<?php echo $numFields; ?>">
			<!-- number of fields wanted in the table -->
			<input type="hidden" name="tableName" value="<?php echo $tableName; ?>">

			<div class="form-group">
				<label for="tableID" class="col-sm-2 control-label">ID</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="tableID" placeholder="Automatically Set Upon Submission" disabled>
				</div>
			</div>

			<?php

				// Display the date time Form Information
				echo $dateTimeHTML;

				
				// Display The Field Information
				echo $fieldHTML;

			?>

			<div class="row">
				<div class="col-sm-6"><a href="./" class="btn btn-block btn-warning hvr-pop">Go Back</a></div>
				<div class="col-sm-6"><button class="btn btn-block btn-success hvr-pop" type="submit">Create Table</button></div>
			</div>

		</form>
	</div>
		

</body>
</html>