<?php

/**
 * Description of Model
 *
 * @author stone
 */
class Model {

	function __construct() {
		//1. model might contains database.
		$this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS); //DB object is reusable in every model
		
		//print_r($this->db);
	}
}
