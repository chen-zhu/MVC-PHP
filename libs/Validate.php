<?php
/**
 * Description of Validate
 *
 * @author stone
 */
class Validate {
	
	public function minLength($data, $arg){
		if(strlen($data) < $arg){
			return "Your string must be longer than $arg chars.";
		}	
	}
	
	public function maxLength($data, $arg){
		if(strlen($data) > $arg){
			return "Your string must be shorter than $arg chars.";
		}	
	}
	
	public function digit($data){
		if(ctype_digit($data) == false){
			return "String must be an integer.";
		}
	}
	
	public function __call($name, $arg){
		throw new Exception("$name does not exist inside of: " . __CLASS__);
	}
	
}
