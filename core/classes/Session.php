<?php

class Session {

	// Put the value into a variable
	public static function put($name, $value, $type = null) {
		
		if($type != '') {
			// Define $msg as blank string
			$msg = '';
			// Switch on $type, and display properly formatted alert box
			switch($type) {
				case 'success':
					$msg .= '<div class="alert alert-success animated" role="alert" id="alert"><p><strong>Success</strong></p><p> ';
				break;
				case 'info':
					$msg .= '<div class="alert alert-info animated" role="alert" id="alert"><p><strong>Information</strong></p><p> ';
				break;
				case 'warning':
					$msg .= '<div class="alert alert-warning animated" role="alert" id="alert"><p><strong>Warning</strong></p><p> ';
				break;
				case 'danger':
					$msg .= '<div class="alert alert-danger animated" role="alert" id="alert"><p><strong>Critical Error</strong></p><p> ';
				break;
				default:
					$msg .= "Alert Error: Contact an Administrator<br>";
					$msg .= '<div class="alert alert-warning animated" role="alert" id="alert"><p><strong>Warning</strong></p><p> ';
				break;

			}

			// Add Message
			$msg .= $value;

			// End HTML
			$msg .= '</p></div>';

			// Store Message into Session
			return $_SESSION[$name] = $msg;

		} else {
			// just store the information in the session
			return $_SESSION[$name] = $value;
		}
		
	}

	// Check if session variable exists
	public static function exists($name) {
		return (isset($_SESSION[$name])) ? true : false;
	}

	// Get session variable value
	public static function get($name) {
		return $_SESSION[$name];
	}

	// Delete the session variable
	public static function delete($name) {
		if(self::exists($name)) {
			unset($_SESSION[$name]);
		}
	}

	// Flash a message to the user
	public static function flash($name, $string = '', $type = 'warning') {

		// If the session exists, return the message
		if(self::exists($name)) {
			// Get the value stored in the session, assign to $session
			$session = self::get($name);
			// Delete the Session
			self::delete($name);
			// Return the Message
			return $session;
		} else {
			// Store the message in the session, passing which type of alert to display
			self::put($name, $string, $type);
		}


	}








}