<?php
/**
 * Handles all Redirects
 */
class Redirect {

	/**
	 *
	 *	Redirects to a specified url
	 *
	 * 	If provided with a valid HTTP Response Status Code, in the form of a numerical value
	 *  respective to the Status Code wanting to be used, then displays the appropriate 
	 * 	status message based upon code.
	 * 	Method can also direct user to an url if provided a valid url:
	 * 	'index.php'
	 * 	'http://google.com/'
	 * 
	 * @param  string|int 		location 		int: HTTP Response Code
	 * 											string: URL
	 * 
	 */
	public static function to($location = null) {
		if($location) {
			// If location is numeric, pull from includes folder
			if(is_numeric($location)) {
				switch($location) {
					case 404:
						// Give a header of 404
						header('HTTP/1.0 404 Not Found');
						// Grab the root directory
						$rootDir = dirname(dirname(__FILE__));
						// include the 404 page
						include $rootDir . '/includes/errors/404.php';
						// exit the script
						exit();
					break;
				}
			}

			// If location is not numeric, go to location
			header('Location: ' . Config::get('links/app_root') . $location);
			// Exit the script
			exit();
		}
	}

}