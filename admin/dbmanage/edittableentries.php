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
					if($_db->tableExists($tableName)) {

						// Grab the Table Fields Array
						$tableFields = $_db->getTableFields($tableName);
						// See if there are any entries in the Table by grabbing the info
						$tableInfo = $_db->getAll($tableName); // if info exists return true
						
						if($tableInfo) { // If there are entries in the table (To Debug add ! before $)
							
							// Loop through each field and grab just the name
							foreach($tableFields as $column){
								$fieldName = $column->Field; // Grab the Field Name
								$tableHeadHTML .= "<th>{$fieldName}</th>";
							}

							// Table Header
							$pageHTML .= "
								<div class='add-entry-wrapper'>
									<a href='addentry.php?tableName={$tableName}' class='btn btn-success'>Add New Entry</a>
								</div>
								<table class='table table-bordered table-hover'>
									<thead>
										<tr>
											{$tableHeadHTML}
											<th>Action</th>
										</tr>
									</thead>
								
							";

							// Grab the rest of the table
							$tableData = $_db->getAll($tableName);
							// Loop through the data
							foreach($tableData as $tableEntry) {
								// Start the Table Row
								$pageHTML .= "<tr>";
								// Grab each entry from the data and loop through that
								foreach($tableEntry as $fieldName => $value) {
									$pageHTML .= "
										<td>{$value}</td>
									";
								}
								// Add Action Buttons
								$pageHTML .= "
									<td>
										<a href='editentry.php?tableName={$tableName}' class='btn btn-info btn-xs hvr-float-shadow'><i class='fa fa-pencil' aria-hidden='true'></i>&nbsp;&nbsp;Edit</a>
										<a href='deleteentry.php?tableName={$tableName}' class='btn btn-danger btn-xs hvr-float-shadow'><i class='fa fa-pencil' aria-hidden='true'></i>&nbsp;&nbsp;Delete</a>
									</td>
								";
								// End the Table Row
								$pageHTML .= "</tr>";
							}


							// End the Table
							$pageHTML .= "</table>";

						} else { // no entries in the selected table
							// output a button to add first entry
							$pageHTML .= "
								<div class='add-entry-wrapper'>
									<h3>This table is emtpy</h3>
									<a href='addentry.php?tableName={$tableName}' class='btn btn-success'>Add New Entry</a>
								</div>
							";
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
	<title>Admin - Edit Entries - Makoto</title>
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
				<li class="active">Edit Table Entries</li>
			</ol>	
		</div>
	
		<?php
			// Session Flash Message
			if(Session::exists('admin-edit-table-entries')) {
				echo Session::flash('admin-edit-table-entries');
			}

			// If 404 Error Occurs
			if(isset($error404)) {
				Redirect::to(404);
			}
		?>
		
		<div class="container">
			<div class="jumbotron">
				<h1>Edit Entries - '<?php if(isset($tableName)) { echo $tableName; } ?>'</h1>
				<p>Please choose an action below</p>
			</div>
		</div>


		<div class="container">


			<?php
				
				echo $pageHTML;
				
			?>


		</div>
</body>
</html>