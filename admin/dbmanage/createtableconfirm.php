<?php
	// Load Initialization File
	require_once '../../core/init.php';
	// Load CSS Name
	$cssFileName = Config::get('links/css_root') . 'admin/' . basename(__FILE__, '.php') . '.css';
	// Load the User
	$user = new User;

	// Is the user logged in
	if($user->isLoggedIn()) {
		// If the user does NOT have admin permission
		if(!$user->hasPermission('admin')) {
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
					echo $dateTime = Input::get('dateTime');
					// Grab Table Name
					$tableName = Input::get('tableName');

					$sql = "CREATE TABLE {$tableName} ( id INT NOT NULL AUTO_INCREMENT , ";

					// Check Date Time
					if($dateTime == true) {
						$sql .= "datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , ";
					}

					// Grab the rest of the SQL from the Input
					for($i = 1; $i <= $numFields; $i++) {
						// Grab Field Name
						$fieldName = Input::get("field" . $i . "name");
						// Add Field Name into SQL
						$sql .= "" . $fieldName . " ";

						// Grab Field Type
						$fieldType = Input::get("field" . $i . "type");
						// Add Field Type Into SQL
						switch($fieldType) {
							case "text":
								$sql .= "TEXT NOT NULL , ";
							break;
							case "int":
								$sql .= "INT NOT NULL , ";
							break;
							case "varchar32":
								$sql .= "VARCHAR(32) NOT NULL , ";
							break;
							case "varchar64":
								$sql .= "VARCHAR(64) NOT NULL , ";
							break;
							case "varchar128":
								$sql .= "VARCHAR(128) NOT NULL , ";
							break;
							case "varchar255":
								$sql .= "VARCHAR(255) NOT NULL , ";
							break;
							
						}

					}
					// Finish the SQL
					$sql .= "PRIMARY KEY (id))";

					echo $sql;

					// Grab new DB Instance
					$_db = DB::getInstance();

					// Submit the SQL to the DB Handler
					if($_db->tableCreate($sql)) {
						echo "Created Table";
						Session::flash('admin-dbmanage', 'You have successfully created the ' . $tableName . ' table.', 'success');
						Redirect::to('admin/dbmanage');
					} else { // Something went wrong when creating the table
						echo "Something went wrong";
						Session::flash('admin-dbmanage', 'Something went wrong when creating the table ' . $tableName , '.', 'danger');
						Redirect::to('admin/dbmanage');
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