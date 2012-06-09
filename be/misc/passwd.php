<?php
/**
 * Functions to hash and verify passwords.
 * 
 * @author Simon Marchi <simon.marchi@polymtl.ca>
 */
 
/**
 * Generates a blowfish salt.
 */
function gen_blowfish_salt() {
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./";
	$len = strlen($chars);
	
	$salt = "$2a$04$";
	
	for ($i = 0; $i < 22; $i++) {
		$salt .= $chars[rand() % $len];
	}

	$salt .= "$";

	return $salt;
}

/**
 * Hashes a password using a pseudo-randmly generated salt.
 */
function crypt_password($password) {
	$salt = gen_blowfish_salt();
	
	$hash = crypt($password, $salt);

	return $hash;
}


/**
 * Checks the validity of a password
 */
function check_password($password, $hash) {
	$hash2 = crypt($password, $hash);

	return $hash2 === $hash;
}

?>
