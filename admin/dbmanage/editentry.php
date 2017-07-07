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
					// Get New DB
					$_db = DB::getInstance();
					// Grab Inputs
					$tableName = Input::get('tableName');
					// Define Variables
					$pageHTML = '';	
					// Grab the Table Fields Array
					$tableFields = $_db->getTableFields($tableName);
					

					// Check and see if table exists
					if(!$_db->tableExists($tableName)) {
						// Table doesnt exist
						$error404 = true;
					}
					
					// Define the Entry ID
					$entryID = Input::get('id');
					// Check to see if entry exists
					if(!$entryData = $_db->get($tableName, array('id', '=', $entryID))->first()) { // If entry doesn't exist
						// error out
						Session::flash('admin-edit-table-entries', 'The id ' . $entryID . ' does not exist!', 'danger');
						Redirect::to("admin/dbmanage/edittableentries.php?tableName={$tableName}");
					}


					// Check and see if Form was Submitted
					if(Input::exists('post') && Token::check(Input::get('token'))) {
						// Form was submitted now do something
						
						// Define the FieldName Array
						$dataArr = array();
						// Define $i
						$i = 1;
						// Loop through all the fields and get the SQL ready
						foreach($tableFields as $column) {
							// Define Field Name
							$fieldName = $column->Field;
							// Skip Fields if field is ID or DateTime
							if($fieldName == 'id' || $fieldName == 'datetime') {
								continue;
							} else {
								$fieldArr[$fieldName] = Input::get("field{$i}");
							}
							// increase counter
							$i++;
						}

						if($_db->update($tableName, $entryID, $fieldArr)) {
							Session::flash('admin-edit-table-entries', "You have successfully updated entry @ id: {$entryID}", 'success');
							Redirect::to("admin/dbmanage/edittableentries.php?tableName={$tableName}");
						} else {
							Session::flash('admin-edit-table-entries', 'Something went wrong while entering your data. Contact an Administrator.', 'danger');
							Redirect::to("admin/dbmanage/edittableentries.php?tableName={$tableName}");
						}
						

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
	<title>Admin - Edit Entry - Makoto</title>
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
		if(Session::exists('admin-edit-entry')) {
			echo Session::flash('admin-edit-entry');
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
			<li class="active">Edit Table Entry</li>
		</ol>	
	</div>

	
	
	<div class="container">
		<div class="jumbotron">
			<h1>Edit Entry - '<?php if(isset($tableName)) { echo $tableName; } ?>'</h1>
			<p>Fill out the information below.</p>
			<p class="text-warning">Note: Integer fields only accept integers. If you enter a non-integer, it will not be entered.</p>
		</div>
	</div>


	<div class="container">
		<form class='form-horizontal' action="" method="post">
		
			<?php
				
				// Define $i
				$i = 1;
				
				// Loop through each field and grab just the name
				foreach($tableFields as $column){

					$fieldName = $column->Field; // Grab the Field Name
					$value = $entryData->$fieldName; // grab the value of the field from the entryData object by the field name

					// Skip Fields if field is ID or DateTime
					if($fieldName == 'id' || $fieldName == 'datetime') {
						continue;
					} else { // Display Form fields for that field
						echo "
							<div class='form-group'>
								<label for='field{$i}' class='col-sm-4 control-label'>{$fieldName}</label>
								<div class='col-sm-8'>
									<input type='text' class='form-control' id='field{$i}' name='field{$i}' value='{$value}' required>
								</div>
							</div>
						";
					}
					$i++;
				}
				
			?>


			<!-- Generate Token -->
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<!-- number of fields wanted in the table -->
			<input type="hidden" name="tableName" value="<?php echo $tableName; ?>">
			<!-- Submit Button -->
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success btn-block hvr-pop">Save Changes</button>
				</div>
			</div>

		</form>
	</div>
</body>
</html>