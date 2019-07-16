<?php

//controller for help!

class Help extends Controller{
	
	function __construct(){
		parent::__construct();
		echo 'we are inside help.<br>'; 
	}
	
	//other method, which will also point function call to model and process data.
	public function other($arg = false){
		echo 'we are inside other. optional arg: ' . $arg . '<br>';
		
		require 'models/help_model.php';
		$model = new help_model();
	}
	
	
}
