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
			if(Input::exists()) {
				if(Token::check(Input::get('token'))) {
				 
					// Grab Inputs
					$tableName = Input::get('tableName');
					$numFields = intval(Input::get('numFields'));

					// Check to see if numFields is 0
					if($numFields === 0) {
						Session::flash('admin-dbmanage', 'The table <strong>' . $tableName . '</strong> must have at least one (1) field.', 'warning');
						Redirect::to('admin/dbmanage');
					}
					// Check to see if numFields is greater then 25
					if($numFields > 10) {
						Session::flash('admin-dbmanage', 'The table <strong>' . $tableName . '</strong> can not have more then ten (10) fields. Use PHPMyAdmin instead.', 'warning');
						Redirect::to('admin/dbmanage');
					}


					// Get New DB
					$_db = DB::getInstance();

					// Check if table Exists in Database already
					if($_db->tableExists($tableName)) {
						Session::flash('admin-dbmanage', 'The table <strong>' . $tableName . '</strong> already exists in the Database', 'danger');
						Redirect::to('admin/dbmanage');
					}
				
				} else {
					Session::flash('admin-dbmanage', 'Something went wrong when creating the table <strong>' . $tableName . '</strong>. Please try again. <strong>Error Code:</strong> createtable', 'danger');
					Session::flash('admin-dbmanage2', 'We have detected that you might have refreshed the page. Thats not allowed. Please use the \'Go Back\' button instead.');
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
	<title>Admin - Create Table - Makoto</title>
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
				<li class="active">Create Table</li>
			</ol>	
		</div>
	
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
			<div class="jumbotron">
				<h1>'<?php if(isset($tableName)) { echo $tableName; } ?>' Information</h1>
				<p>Please fill out the information below, and then click 'Go To Summary Page' to confirm the information you provide.</p>
			</div>
		</div>


		<div class="container form-wrapper">
			<h3>Table Fields & Values</h3>
			<form class="form-horizontal" action="createtablesummary.php" method="post">
				<!-- Generate Token -->
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<!-- number of fields wanted in the table -->
				<input type="hidden" name="numFields" value="<?php echo $numFields; ?>">
				<!-- Table Name -->
				<input type="hidden" name="tableName" value="<?php echo $tableName; ?>">


				<div class="form-group">
					<label for="tableID" class="col-sm-2 control-label">ID</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="tableID" placeholder="Automatically Set Upon Submission" disabled>
					</div>
				</div>

				<div class="form-group">
					<label for="tableName" class="col-sm-2 control-label">Date/Time</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" name="dateTime" value="true" required> <span class="text-info">Add a Date/Time field</span>
						</label>
						<label class="radio-inline">
							<input type="radio" name="dateTime" value="false"> <span class="text-danger">Don't add a Date/Time field</span>
						</label>
					</div>
				</div>


				<?php
					// Dynamically generate number of fields and type
					
					for($i = 1; $i <= $numFields; $i++) {
						
						

						echo "

							<div class='form-group'>
								<label for='tableName' class='col-sm-2 control-label'>Field {$i}</label>
								<div class='col-sm-10'>
									<div class='col-sm-6'>
										<input type='text' class='form-control' id='field{$i}' name='field{$i}name' placeholder='Name of Field' pattern='^\S+$' title='No Spaces are allowed' required>
									</div>
									<div class='col-sm-6'>
										<select name='field{$i}type' class='form-control' required>
											<option value='' disabled selected hidden>Type of Field</option>
											<option value='' disabled>Hover Each Type For Description</option>
											<option value='' disabled>-------------------------------</option>
											<option value='text' title='Holds a string with a maximum length of 65,535 characters'>Text</option>
											<option value='int' title='-2147483648 to 2147483647 normal. 0 to 4294967295 UNSIGNED*.'>Integer</option>
											<option value='varchar32' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (32)</option>
											<option value='varchar64' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (64)</option>
											<option value='varchar128' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (128)</option>
											<option value='varchar255' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (255)</option>
										</select>
									</div>
								</div>
							</div>

						";

					}


				?>

				<div class="row">
					<div class="col-sm-6"><a href="./" class="btn btn-block btn-warning hvr-pop">Go Back</a></div>
					<div class="col-sm-6"><button class="btn btn-block btn-info hvr-pop" type="submit">Go To Summary Page</button></div>
				</div>

			</form>
		</div>
		

</body>
</html>