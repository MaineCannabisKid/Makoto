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
				 
					// Grab Inputs
					$tableName = Input::get('tableName');

					// Get New DB
					$_db = DB::getInstance();
				
					$tableFields = $_db->getTableFields($tableName);

					// Enable this line below for debugging
					// echo '<pre>' , print_r($tableFields) , '</pre>';

									
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
				<li class="active">Edit Table Fields</li>
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
				<p>Please edit the information below, and then click 'Save'.</p>
			</div>
		</div>


		<div class="container form-wrapper">
			<h3>Table Fields & Values</h3>
			<form class="form-horizontal" action="edittableconfirm.php" method="post">
				<!-- Generate Token -->
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				<!-- number of fields wanted in the table -->
				<input type="hidden" name="numFields" value="<?php echo $numFields; ?>">
				<!-- Table Name -->
				<input type="hidden" name="tableName" value="<?php echo $tableName; ?>">


				<?php
					// Dynamically generate number of fields and type
					
					$i = 1;
					foreach($tableFields as $column){

						$fieldName = $column->Field;
						$fieldType = $column->Type;
						// Skip Fields if field is ID or DateTime
						if($fieldName == 'id' || $fieldName == 'datetime') {
							continue;
						} else {
							switch($fieldType) {
								case "text":
									$fieldTypeHTML = "
										<option value='' disabled>Hover Each Type For Description</option>
										<option value='' disabled>-------------------------------</option>
										<option value='text' title='Holds a string with a maximum length of 65,535 characters' selected>Text</option>
										<option value='int' title='-2147483648 to 2147483647 normal. 0 to 4294967295 UNSIGNED*.'>Integer</option>
										<option value='varchar32' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (32)</option>
										<option value='varchar64' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (64)</option>
										<option value='varchar128' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (128)</option>
										<option value='varchar255' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (255)</option>
									";
								break;
								case "int(11)":
									$fieldTypeHTML = "
										<option value='' disabled>Hover Each Type For Description</option>
										<option value='' disabled>-------------------------------</option>
										<option value='text' title='Holds a string with a maximum length of 65,535 characters'>Text</option>
										<option value='int' title='-2147483648 to 2147483647 normal. 0 to 4294967295 UNSIGNED*.' selected>Integer</option>
										<option value='varchar32' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (32)</option>
										<option value='varchar64' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (64)</option>
										<option value='varchar128' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (128)</option>
										<option value='varchar255' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (255)</option>
									";
								break;
								case "varchar(32)":
									$fieldTypeHTML = "
										<option value='' disabled>Hover Each Type For Description</option>
										<option value='' disabled>-------------------------------</option>
										<option value='text' title='Holds a string with a maximum length of 65,535 characters'>Text</option>
										<option value='int' title='-2147483648 to 2147483647 normal. 0 to 4294967295 UNSIGNED*.'>Integer</option>
										<option value='varchar32' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.' selected>Varchar (32)</option>
										<option value='varchar64' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (64)</option>
										<option value='varchar128' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (128)</option>
										<option value='varchar255' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (255)</option>
									";
								break;
								case "varchar(64)":
									$fieldTypeHTML = "
										<option value='' disabled>Hover Each Type For Description</option>
										<option value='' disabled>-------------------------------</option>
										<option value='text' title='Holds a string with a maximum length of 65,535 characters'>Text</option>
										<option value='int' title='-2147483648 to 2147483647 normal. 0 to 4294967295 UNSIGNED*.'>Integer</option>
										<option value='varchar32' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (32)</option>
										<option value='varchar64' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.' selected>Varchar (64)</option>
										<option value='varchar128' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (128)</option>
										<option value='varchar255' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (255)</option>
									";
								break;
								case "varchar(128)":
									$fieldTypeHTML = "
										<option value='' disabled>Hover Each Type For Description</option>
										<option value='' disabled>-------------------------------</option>
										<option value='text' title='Holds a string with a maximum length of 65,535 characters'>Text</option>
										<option value='int' title='-2147483648 to 2147483647 normal. 0 to 4294967295 UNSIGNED*.'>Integer</option>
										<option value='varchar32' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (32)</option>
										<option value='varchar64' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (64)</option>
										<option value='varchar128' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.' selected>Varchar (128)</option>
										<option value='varchar255' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (255)</option>
									";
								break;
								case "varchar(255)":
									$fieldTypeHTML = "
										<option value='' disabled>Hover Each Type For Description</option>
										<option value='' disabled>-------------------------------</option>
										<option value='text' title='Holds a string with a maximum length of 65,535 characters'>Text</option>
										<option value='int' title='-2147483648 to 2147483647 normal. 0 to 4294967295 UNSIGNED*.'>Integer</option>
										<option value='varchar32' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (32)</option>
										<option value='varchar64' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (64)</option>
										<option value='varchar128' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.'>Varchar (128)</option>
										<option value='varchar255' title='Holds a string (can contain letters, numbers, and special characters). The maximum size is specified in parenthesis. Can store up to 255 Characters.' selected>Varchar (255)</option>
									";
								break;
								
							}


							echo "
								<div class='form-group'>
									<label for='tableName' class='col-sm-2 control-label'>Field {$i}</label>
									<div class='col-sm-10'>
										<div class='col-sm-6'>
											<input type='text' class='form-control' id='field{$i}' name='field{$i}name' value='{$fieldName}' pattern='^\S+$' title='No Spaces are allowed' required>
										</div>
										<div class='col-sm-6'>
											<select name='field{$i}type' class='form-control' required>
												{$fieldTypeHTML}
											</select>
										</div>
									</div>
								</div>
							";

							$i++;
						}




					}


					


				?>

				<div class="row">
					<div class="col-sm-6"><a href="./" class="btn btn-block btn-warning hvr-pop">Go Back</a></div>
					<div class="col-sm-6"><button class="btn btn-block btn-success hvr-pop" type="submit">Save</button></div>
				</div>

			</form>
		</div>
		

</body>
</html>