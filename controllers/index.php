<?php

//index controler

class Index extends Controller{
	
	function __construct(){
		parent::__construct();
	}
	
	public function index(){
		//calling render function from view object to complete UI!
		$this->view->render('index/index');
	}
	
	public function details(){
		echo '12345';
		$this->view->render('index/index');
	}
}



