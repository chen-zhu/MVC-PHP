<?php

//Error controller
class Errors extends Controller{
	
	function __construct() {
		parent::__construct();
		echo 'This is error!';
		
		//calling render function from view object to complete UI!
		$this->view->msg = 'This page does not exist!';
		$this->view->render('errors/index');
	}
}
