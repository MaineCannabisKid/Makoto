<?php
/**
 *	Handles all Hashing
 */
class Hash {

	/**
	 * 
	 *	Hash a string with an optional salt
	 *
	 * 	Providing a salt is completely optional, however for increased
	 * 	security we do recommend it
	 * 
	 * @param  string 			string 			String that you want hashed		
	 * @param  string 			salt 			(Optional) Salt that you want added
	 * @return string 							Returns the hashed value of 'string'
	 */
	public static function make($string, $salt = '') {
		return hash('sha256', $string . $salt);
	}

	/**
	 * @param  int 				length 			Length of the salt you want to generate
	 * @return string|boolean					Returns salt in the form of a string, 
	 * 											or false if not able to create
	 */
	public static function salt($length) {
		return mcrypt_create_iv($length);
	}

	/**
	 *
	 *	Generate a new random hash
	 *
	 * 	Upon calling this function, uniqid generates a random value, then it passes it to
	 * 	make() which then hashes the value.
	 * 
	 *	@return string  						Returns randomly hashed value
	 */	
	public static function unique() {
		return self::make(uniqid());
	}

}