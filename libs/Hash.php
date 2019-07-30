<?php

class Hash {
	
	/**
	 * 
	 * @param string $algo algorithm (md5, sha1, etc)
	 * @param string $data string to be encoded.
	 * @param string $salt The salt string which will be used for encryption.
	 * @return string The hashed/salted string.
	 */
	public static function create($algo, $data, $salt = HASH_KEY){
		$context = hash_init($algo, HASH_HMAC, $salt);
		hash_update($context, $data);
		
		return hash_final($context);
	}
	
	
	
}
