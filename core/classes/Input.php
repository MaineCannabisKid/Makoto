<?php
/**
 *	Handles all Input Forms
 */
class Input {


	// Check and see if input exists
	/**
	 *
	 *	Check and see if the input exists
	 *
	 * 	When the function is called it checks to see if values are defined within the input
	 * 	array variable $_GET or $_POST. Returns true/false if there is/isn't input.
	 * 
	 * @param  string 		type 		Defined values: post|get
	 * @return boolean 					Returns true/false depending on if data exists within
	 * 									the specified input array type get/post
	 * 									
	*/
	public static function exists($type = 'post') {
		switch($type) {
			case 'post':
				return (!empty($_POST)) ? true : false;
			break;
			case 'get':
				return (!empty($_GET)) ? true : false;
			break;
			default:
				return false;
			break;
		}
	}

	/**
	 *
	 * Get and item from $_POST or $_GET if set
	 * 
	 * @param  string 		item 		Specifies what input field you're looking for
	 * @return string 					Returns the value of the input field you're looking for
	 * 
	 */
	public static function get($item) {
		if(isset($_POST[$item])) {
			return $_POST[$item];
		} else if(isset($_GET[$item])) {
			return $_GET[$item];
		}
		return '';
	}

}