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
		if(!$user->hasPermission(array('admin'))) {
			// User Not Logged In Redirect to Home Page
			Session::flash('home', 'You do not have permission to view that page', 'danger');
			Redirect::to('index.php');
		} else { // Authorization correct
			// Does input in the URL exist?
			if(Input::exists()) {
				if(Token::check(Input::get('token'))) {

					// Grab the ID from the URL using GET
					$tableToDel = Input::get('tableToDel');
					switch($tableToDel) {
						case "":
						case null:
							Session::flash('admin-dbmanage', 'No table to delete', 'danger');
							Redirect::to('admin/dbmanage');
						break;
						case "users":
						case "user_session":
						case "groups":
							Session::flash('admin-dbmanage', 'The table <strong>' . $tableToDel . '</strong> can not be deleted', 'danger');
							Redirect::to('admin/dbmanage');
						break;
					}

					// Get a new instance of the DB
					$_db = DB::getInstance();
					
					// Check and see if the table exists first, if it doesn't redirect
					if(!$_db->tableExists($tableToDel)) {
						Session::flash('admin-dbmanage', 'The table <strong>' . $tableToDel . '</strong> does not exist', 'danger');
						Redirect::to('admin/dbmanage');
					} else { // Table does exist
						if($_db->tableDel($tableToDel)) { // If table was deleted
							Session::flash('admin-dbmanage', 'The table <strong>' . $tableToDel . '</strong> has been successfully deleted', 'success');
							Redirect::to('admin/dbmanage');
						} else { // If something went wrong
							Session::flash('admin-dbmanage', 'Something went wrong when deleting the table', 'danger');
							Redirect::to('admin/dbmanage');
						}
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