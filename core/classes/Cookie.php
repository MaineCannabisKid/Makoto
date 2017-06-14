<?php
/**
 *	Handles all Cookie Functionality
 */
class Cookie {

	/**
	 * 
	*	Check to see if the cookie exists
	* 
	*	@return boolean						True: Exists | False: Does Not Exist
	*/
	public static function exists($name) {
		return (isset($_COOKIE[$name])) ? true : false;
	}

	/**
	 * 
	 *	Gets the value of a cookie
	 * 
	 *	@param 	string 			name 		Name of the Cookie
	 *	@return 							Value of the Cookie
	 */
	public static function get($name) {
		return $_COOKIE[$name];
	}

	/**
	 * 
	 * 	Sets a cookie
	 * 
	 *	@param  string 			name 		Name of the Cookie
	 *	@param  various 		value 		Value that you want stored in the cookie
	 *	@param  int 			expiry 		The number of seconds until expiration of cookie
	 *	@return boolean						True: Created Cookie | False: Failed at Creating Cookie
	 */
	public static function put($name, $value, $expiry) {
		if(setcookie($name, $value, time() + $expiry, '/')) {
			return true;
		}
		return false;
	}

	/**
	 * 
	 *	Deletes a Cookie
	 * 
	 *	@param  string  		name 		Name of the cookie you want to delete
	 */
	public static function delete($name) {
		self::put($name, '', time() - 1);
	}

}

?>