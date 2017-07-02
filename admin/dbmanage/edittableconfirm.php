<?php 
	// Load Initialization File
	require_once '../../core/init.php';
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
				// if(Token::check(Input::get('token'))) {
				 
					// Grab Inputs
					$tableName = Input::get('tableName');

					// Start the SQL
					$sql = "ALTER TABLE {$tableName} ";
					// Get New DB
					$_db = DB::getInstance();
					
					// Get the Table Fields
					$tableFields = $_db->getTableFields($tableName);
					// Grab Number of Fields -2 becaue of id and DateTime
					$numFields = count($tableFields) - 2;

					$i = 1;
					foreach($tableFields as $column){

						$oldFieldName = $column->Field;
						$oldFieldType = $column->Type;
						// Skip Fields if field is ID or DateTime
						if($oldFieldName == 'id' || $oldFieldName == 'datetime') {
							continue;
						} else {
							// Grab the new Field Name and Type
							$newFieldType = Input::get('field' . $i . 'type');
							$newFieldName = Input::get('field' . $i . 'name');

							switch($newFieldType) {
								case "text":
									$newFieldType = "TEXT";
								break;
								case "int":
									$newFieldType = "INT";
								break;
								case "varchar32":
									$newFieldType = "VARCHAR(32)";
								break;
								case "varchar64":
									$newFieldType = "VARCHAR(64)";
								break;
								case "varchar128":
									$newFieldType = "VARCHAR(128)";
								break;
								case "varchar255":
									$newFieldType = "VARCHAR(255)";
								break;
								
							}


							// Add to the SQL
							$sql .= "CHANGE {$oldFieldName} {$newFieldName} {$newFieldType} NOT NULL";
							// Make sure there is no comma at the end of the SQL.
							if($i < count($tableFields) - 2) {
								$sql .= ', ';
							}

							
							echo "Old Field: " . $oldFieldName . " : " . $oldFieldType . "<br>";
							echo "New Field: " . $newFieldName . " : " . $newFieldType . "<br>";
							echo "<br>";
							$i++;
						}
					}

					// Echo SQL
					echo $sql . "<br><br>";

					// Execute the SQL
					if($_db->query($sql)) {
						Session::flash('admin-dbmanage', 'You have successfully changed the table fields', 'success');
						Redirect::to('admin/dbmanage');
					} else {
						Session::flash('admin-dbmanage', 'Something went wrong when creating the table. Error: #tber', 'danger');
						Redirect::to('admin/dbmanage');
					}

					

					// Enable this line below for debugging
					echo '<pre>' , print_r($tableFields) , '</pre>';

				// } else {
				// 	Session::flash('admin-dbmanage', 'Something went wrong editing the table. Error: #tk0', 'danger');
				// 	Redirect::to('admin/dbmanage');
				// }					
			} else { // Input does not exist
				$error404 = true;
			}

		}
	} else {
		// User Not Logged In Redirect to Home Page
		Session::flash('home', 'You are not logged in.', 'warning');
		Redirect::to('index.php');
	}
