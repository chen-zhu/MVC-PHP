<?php

//index controler

class Login extends Controller{
	
	function __construct(){
		parent::__construct();
	}
	
	public function index(){
		//calling render function from view object to complete UI!
		$this->view->render('login/index');
	}
	
	public function run(){
		$this->model->run();
	}
	
}



