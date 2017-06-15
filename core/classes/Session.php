<?php

class Session {

	// Put the value into a variable
	public static function put($name, $value, $type = '') {
		
		if($type != '') {
			// Define $msg as blank string
			$msg = '';

			switch($type) {
				case 'success':
					$msg .= '<div class="container"><div class="alert alert-success"><p>';
				break;
				case 'info':
					$msg .= '<div class="container"><div class="alert alert-info"><p>';
				break;
				case 'warning':
					$msg .= '<div class="container"><div class="alert alert-warning"><p>';
				break;
				case 'danger':
					$msg .= '<div class="container"><div class="alert alert-danger"><p>';
				break;

			}

			// Add Message
			$msg .= $value;

			// End HTML
			$msg .= '</p></div></div>';

			// Store Message into Session
			return $_SESSION[$name] = $msg;

		} else {
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
	public static function flash($name, $string = '', $type = '') {

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