<?php

Class Search {

	// Declare Variables
	private $_db,
			$_t,
			$_k;


	// Construct
	public function __construct($t, $k) {
		// Grab New Instance of DB and store it in _db
		$this->_db = DB::getInstance();
		// Table
		$this->_t = escape($t);
		// Keywords
		$this->_k = escape($k);

		// Validate Search Input
		$this->inputValidation($this->_t, $this->_k);

		
	}

	// Execute the Search
	public function execute() {
		// Define $t and $k for simpler use
		$t = $this->_t;
		$k = $this->_k;

		// Switch on the Table and execute the correct search based on results
		// Each "Search Category" should have its own Private Function
		// i.e. $this->searchUsers($k);
		// i.e. $this->searchParts($k);
		// 
		// This is to avoid any confusion on what is being searched for categorically speaking
		switch($t) {

			// Search the Users Table
			case "users":
				// Return the Results
				return $this->searchUsers($k);
			break;


			// Search Some Other Table
			case "test":
				// New Private Search Function
				return "
				This functionality doesn't really work yet until you configure Makoto to your own use.
				<br>
				<strong>
					Use Search Users Instead!
				</strong>";
			break;


		}
		
	}

	// Check the Input to see if the user messed around with the GET variables in the URL
	private function inputValidation($searchTable, $searchKeywords) {

		// Check to see if Table is null
		if(!$searchTable) {
			// Contact an admin as table is hardcoded into fields with a default value set
			Session::flash('home', 'Error with searching, contact an administrator.<br>Error Code: s.T-Err.N', 'danger');
			Redirect::to('index.php');
		}

		// Check to see if table exists
		if(!$this->_db->tableExists($searchTable)) {
			// Table Does Not Exist so Error Out
			Session::flash('home', 'Error with searching, contact an administrator.<br>Error Code: s.T-Err.DNE', 'danger');
			Redirect::to('index.php');
		}

		// Check to see if Keywords are null
		if(!$searchKeywords) {
			// Can not search for nothing
			Session::flash('home', 'You can not search for nothing. Please re-enter your search.', 'warning');
			Redirect::to('index.php');
		}

	}

	//////////////////////////
	// ******************** //
	// * Search Functions * //
	// ******************** //
	//////////////////////////
	
	// All search functions are to be //

	// PRIVATE functions
	// &
	// Return Search Results as a HTML String

	private function searchUsers($k) {
		// Get the SQL together
		// Limit to 36 top results
		$sql = "
			SELECT id, username, name, picture, groups
			FROM users
			WHERE username LIKE '%{$k}%'
			OR name LIKE '%{$k}%'
			ORDER by groups DESC 
			LIMIT 36
		";

		// Execute the query
		if($this->_db->query($sql)) { // Query Successfully Executed
			// Define Jumbotron Information HTML
			$HTML = "
				<div class='jumbotron'>
					<h1>User Search</h1>
			";
			// Get Results
			$results = $this->_db->results();
			// Get Count
			$count = $this->_db->count();
			
			
			if(!$results) { // If there are no results

				// No Results end jumbotron div
				$HTML .= "<p>No Results For: {$k}</p></div>";

			} else { // There are results
				// End Jumbotron Div and Start Users Box
				$HTML .= "<p>Here are your top <strong>{$count}</strong> results for: <strong>{$k}</strong></p></div><div class='row search-users'>";
				// Put the Results in a Bootstrap formated HTML
				foreach($results as $user) {
					// Assign the correct String for the Numeric value of Role
					switch($user->groups) {
						case "3":
							$roleClass = "img-admin";
							$role = "<span class='text-danger'>Admin</span>&nbsp;";
						break;
						case "2":
							$roleClass = "img-mod";
							$role = "<span class='text-success'>Mod</span>&nbsp;";
						break;
						default:
							$roleClass = "";
							$role = "";
						break;
					}
					// Grab the Users Picture
					$picture = $user->picture;
					// If its null give the new default picture
					if(!$picture) {
						$picture = Config::get('links/app_root') . "assets/imgs/profile/default.png";
					}
					// Construct some HTML
					$HTML .= "
						<div class='col-sm-4 user'>
							<div class='col-sm-3 profile-img'><img src='{$picture}' class='{$roleClass}'></div>
							<div class='col-sm-9'>
								<div class='col-sm-12 user-wrapper username'>
									<a href='" . Config::get('links/app_root') . "profile/index.php?user={$user->id}'>
										@{$user->username}
									</a>
								</div>
								<div class='col-sm-12 user-wrapper name'>
									{$role}({$user->name})
								</div>
							</div>
						</div>
					";
				}

				// Close the Table
				$HTML .= "</div>";

			}
			
			// Return HTML Table as STRING
			return $HTML;
		} else {
			// Something went wrong
			Session::flash('home', 'Contact an Admin.<br>Error Code: s.U-Err.q-f', 'danger');
			Redirect::to('index.php');
		}


	}



	
}