<?php

//index controler

class Dashboard extends Controller{
	
	function __construct(){
		parent::__construct();
		Session::init();
		$logged = Session::get('loggedIn');
		if($logged == false){
			Session::destroy();//in case.
			header('location: ../login'); //bring users back to login page. 
			exit;
		}
	}
	
	public function index(){
		//calling render function from view object to complete UI!
		$this->view->render('dashboard/index');
	}
	
	public function logout(){
		Session::destroy();
		header('location: ../login'); //bring users back to login page. 
		exit;
	}
	
}



