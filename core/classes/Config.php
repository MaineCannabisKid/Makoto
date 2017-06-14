<?php

class Config {
	
	// Get Config bit depending on Path Specified by User
	public static function get($path = null) {
		if($path) {
			// Grab the Globals Config
			$config = $GLOBALS['config'];
			// Explode the path and throw into array
			$path = explode('/', $path);
			// Get access to each bit of information
			foreach($path as $bit) {
				// Loop Through and grab Information
				if(isset($config[$bit])) {
					// Set the previous Bit to Config
					$config = $config[$bit];
				} else {
					// If Empty Return False
					return false;
				}
				// Grab Next Bit When Looping Back
			}
			// Return Last Bit Grabbed
			return $config;
		}
		// Return False if error happened
		echo "<h1>SOMETHING HAPPENED IN CONFIG.PHP</h1>";
		return false;
	}


}