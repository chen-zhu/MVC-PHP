<?php

//A databse should only be accessible in Model!
class Database extends PDO{
	
	public function __construct() {
		parent::__construct(
					DB_TYPE . ':host=' . DB_HOST.';dbname=' . DB_NAME, 
					DB_USER, 
					DB_PASS, 
					array(
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC
					)
				);
	}
	
}