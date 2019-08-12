<?php

/**
 * This is a form handler
 * 
 * 1. Validate
 * 2. Sanitize
 * 
 * usage (sample user insert):
 * Please make sure to wrap this class inside try & catch!
 *	$form = new Form();
 *	$form	->post()
 *			->validate()
 *			->post()
 *			->validate()
 *			->validate();
 *	$form->submit();
 *	$form_data = $form->fetch();
 */
require_once "Validate.php";

class Form {
	
	private $_currentItem = NULL; //The immediately posted item.
	private $_postData = array();
	private $_val = array(); //it will hold the validation object
	private $_error = array(); //holds the current forms errors.
	
	public function __construct() {
		$this->_val = new Validate();
	}
	
	/**
	 * This is to run $_POST
	 * 
	 * @param type $field
	 */
	public function post($field){
		$this->_postData[$field] = $_POST[$field];
		$this->_currentItem = $field;
		return $this; //return the self object, which will be used as a chain later on!
	}
	
	/**
	 * Return the posted data!
	 * 
	 * @param string $fieldName
	 * @return mixed
	 */
	public function fetch($fieldName){
		if($fieldName){
			if(isset($this->_postData[$fieldName])){
				return $this->_postData[$fieldName];
			} else {
				return false;
			}
		} else {
			return $this->_postData;
		}
	}
	
	/**
	 * This function will be used to call validate class.
	 * 
	 * @param string $typeOfValidation
	 * @param string $arg NULL by default.
	 * @return $this
	 */
	public function validate($typeOfValidation, $arg = NULL){
		//passing in the value of the field we are posting.
		if($arg === NULL){
			$error = $this->_val->{$typeOfValidation}($this->_postData[$this->_currentItem]);
		} else {
			$error = $this->_val->{$typeOfValidation}($this->_postData[$this->_currentItem], $arg);
		}
		if($error){
			$this->_error[$this->_currentItem] = $error;
		}
		
		return $this;
	}
	
	/**
	 * Handles the form. 
	 * If error is generated, this function will throw exception!
	 * @return type
	 * @throws Exception
	 */
	public function submit(){
		if(empty($this->_error)){
			return;
		} else {
			$msg = '';
			foreach($this->_error as $key => $val){
				$msg .= $key . ": " . $val . "\n";
			}
			throw new Exception($this->_error);
		}
	}
	
}
