<?php

class User {
	// Define Private Variables
	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn;


	// Grab the DB Instance upon load
	public function __construct($user = null) {
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		// If user was not defined upon calling user object
		if(!$user) {
			// Check if OOP Login Session was set
			if(Session::exists($this->_sessionName)) {
				// Grab current User ID from the Session
				$user = Session::get($this->_sessionName);
				// Check if the user actually exists or not in the users DB
				if($this->find($user)) {
					// If everything is good, the user is logged in
					$this->_isLoggedIn = true;
				} else {
					// process logout
					self::logout();
					Session::flash('home', 'Something went wrong with the User Class');
					Redirect::to('index.php');
				}
			}
		} 
		// Else if user was defined
		else {
			$this->find($user);
		}
	}

	// Create a user by adding them into the database
	public function create($fields = array()) {
		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating a new account');
		} else {
			return true;
		}
	}


	// Delete a user from the database
	public function delete($userID) {
		if(!$this->_db->delete('users', array('id', '=', $userID))) {
			throw new Exception('There was a problem deleting the user ' . $userID);
		} else {
			return true;
		}
	}

	// Find a user in the database
	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'email';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	// Login
	public function login($email = null, $password = null, $remember = false) {

		// Check if user logged in
		if(!$email && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);

		} else {
			// Find the user in the database
			$user = $this->find($email);
			// If user is found
			if($user) {
				// If the passwords match
				if($this->data()->password === Hash::make($password, $this->data()->salt)) {
					// Log User In by Creating a session and storing the user ID in it
					Session::put($this->_sessionName, $this->data()->id);

					// Check if remember is true
					if($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('user_session', array('user_id', '=', $this->data()->id));

						// If hashCheck returns false
						if(!$hashCheck->count()) {
							$this->_db->insert('user_session', array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}

						// Set a cookie
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				} else {
					// Password didn't match the system, display Reset Password Page (Once created)
					Session::flash('login', 'Did you forget your password? Click here to reset', 'warning');
					Redirect::to('login.php');
				}
				
			} else {
				// User was not found
				// Redirect to Register Page
				Session::flash('register', 'That e-mail is not a registered user. Please register to log in', 'warning');
				Redirect::to('register.php');
			}
		}
		
		// Login Failed, return false
		return false;
	}

	// Google Login
	public function googleLogin($googleUser) {
		
		// Log User In by Creating a session and storing the user ID in it
		Session::put($this->_sessionName, $this->data()->id);

		// Check the user_session table to see if user if logged in
		$hashCheck = $this->_db->get('user_session', array('user_id', '=', $this->data()->id));

		// If user is not in the user_session table
		if(!$hashCheck->count()) {
			// Make a new hash
			$hash = Hash::unique();
			// Insert user into the user_session table
			$this->_db->insert('user_session', array(
				'user_id' => $this->data()->id,
				'hash' => $hash
			));
		} else {
			// User must be logged in so grab the hash from the Database
			$hash = $hashCheck->first()->hash;
		}

		// Set a cookie with the hash in it
		Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));

		// Check google information against our own, and update if needed
		if(!$this->checkGoogleInfo($googleUser)) {
			// Update the system if out of date
			$this->updateGoogleInfo($googleUser);
		}

		return true;
	}

	// Check Google Information and compare it to our own information
	private function checkGoogleInfo($googleUser) {
		// Define the GoogleId from OUR systems
		$userGoogleId = $this->data()->google_id;
		// Check and see if googleId in our system is NULL
		if($userGoogleId === null) { // If the GoogleId in the system is NULL
			return false;
		}
		// Else Google Information is fine
		return true;
	}

	private function updateGoogleInfo($googleUser) {
		// If the Update to reflect google's info in our system fails
		if($this->update(array(
			'google_id' => $googleUser['sub'],
			'name' => $googleUser['name'],
			'picture' => $googleUser['picture'],
			'email_verified' => $googleUser['email_verified']
		), $this->data()->id)) { // Updated sucessfully
			return true;
		} else { 
			// Do thise
			die("Something went wrong updating the users table during googleLogin()");
		}
	}

	// Update User
	public function update($fields = array(), $id = null) {
		// If id is null but user is logged in use the current user's id
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}
		// If there were errors Updating, throw new Exception
		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating');
		} else {
			return true;
		}
	}

	// User Has Permission Check
	public function hasPermission($key = array('admin')) {
		$groups = $this->_db->get('groups', array('id', '=', $this->data()->groups));

		// Check if user is in a group or not
		if($groups->count()) {
			// Grab the json from the user
			$permissions = json_decode($groups->first()->permissions, true);
			for($i=0;$i<=count($key)-1; $i++) {
				if(isset($permissions[$key[$i]])) {
					return true;
				}
			}
		}

		// user does not have permission
		return false;

	}

	// Check if the data exists
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	// Logout
	public function logout() {
		// Sign Out from Google
		Session::delete('access_token');
		// Sign out from OOP Login System
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
		$this->_db->delete('user_session', array('user_id', '=', $this->data()->id));

	}

	// Return data
	public function data() {
		return $this->_data;
	}

	// Is the User Logged In
	public function isLoggedIn() {
		// Check if user logged in via Google Auth
		if(isset($_SESSION['access_token'])) {
			$this->_isLoggedIn = true;
		}
		// Return _isLoggedIn;
		return $this->_isLoggedIn;
	}

}