<?php

//controller for help!

class Help extends Controller{
	
	function __construct(){
		parent::__construct();
	}
	
	public function index(){
		//calling render function from view object to complete UI!
		$this->view->render('help/index');
	}

	//other method, which will also point function call to model and process data.
	public function other($arg = false){
		
		require 'models/help_model.php';
		$model = new help_model();
		$this->view->blah = $model->blah();
	}
	
	
}
