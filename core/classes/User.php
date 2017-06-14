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
			// Check if session exists
			if(Session::exists($this->_sessionName)) {
				// Define the user
				$user = Session::get($this->_sessionName);
				// Check if the user actually exists or not
				if($this->find($user)) {
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
		}
	}

	// Find a user in the database
	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	// Login
	public function login($username = null, $password = null, $remember = false) {

		// Check if user logged in
		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);

		} else {
			$user = $this->find($username);
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
				}
			}
		}
		
		// Login Failed, return false
		return false;
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
		}
	}

	// User Has Permission Check
	public function hasPermission($key) {
		$groups = $this->_db->get('groups', array('id', '=', $this->data()->groups));

		// Check if user is in a group or not
		if($groups->count()) {
			$permissions = json_decode($groups->first()->permissions, true);
			if(!empty($permissions[$key])) {
				return true;
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

		$this->_db->delete('user_session', array('user_id', '=', $this->data()->id));

		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	// Return data
	public function data() {
		return $this->_data;
	}

	// 
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

}