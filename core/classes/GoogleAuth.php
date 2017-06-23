<?php
class GoogleAuth {

	// Define Variables
	protected 	$_client,
				$_db;

	public function __construct(Google_Client $googleClient = null) {
		$_db = DB::getInstance();
		// Load the Google Client into Protected Variable _client
		$this->_client = $googleClient;
		// If the Google Client is not null
		if($this->_client) {
			// Did the Google Client Load Successfully
			// echo 'Google Client Successfully Loaded <br>';
			// Set Client ID, Client Secret, Redirect URI, & Scope
			$this->_client->setClientId('320697062223-8bp9flv9i4c8j2vn69vpnfqc73ab1qak.apps.googleusercontent.com');
			$this->_client->setClientSecret('tOMCk8znGQ33tqn9pWtGkNKi');
			$this->_client->setRedirectURI('http://localhost/OOP%20Login%20System/googleAuthRedirect.php');
			$this->_client->addScope('https://www.googleapis.com/auth/userinfo.email');
			$this->_client->addScope('https://www.googleapis.com/auth/userinfo.profile');
		}


	}


	// Get the Authorization URL from Google
	public function getAuthUrl() {
		// Return the URL
		return $this->_client->createAuthUrl();
	}

	// Check the Redirect Code on the googleAuthRedirect.php page
	public function checkRedirectCode() {
		// if the code is set
		if(isset($_GET['code'])) {

			// Check code sent from google against google's servers
			$this->_client->authenticate($_GET['code']);

			// Get the access token from the google client
			$token = $this->_client->getAccessToken();

			// and then set it - Logging the user in
			Session::put('access_token', $token);

			// Set the Access Token so that we're now logged in
			$this->_client->setAccessToken($token);

			// Set the token
			return true;
		}
		// Something went wrong
		return false;
	}

	// Get user information (Payload)
	public function getPayload() {
		// Grabs the array of data about the user
		$payload = $this->_client->verifyIDToken();
		// returns the array
		return $payload;
	}










}