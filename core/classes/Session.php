<?php

class Session {

	// Put the value into a variable
	public static function put($name, $value) {
		return $_SESSION[$name] = $value;
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
	public static function flash($name, $string = '') {
		if(self::exists($name)) {
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else {
			self::put($name, $string);
		}
	}

}