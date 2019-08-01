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
		
		$this->view->js = array('dashboard/js/default.js'); //it will be used to load js script for dashboard.
	}
	
	public function index(){
		//calling render function from view object to complete UI!
		$this->view->render('dashboard/index');
	}
	
	public function logout(){
		Session::destroy();
		header('location: ' . URL . 'login'); //bring users back to login page. 
		exit;
	}
	
	//xhr --> XML HTTP Request
	public function xhrInsert(){
		//call dashbaord model here to perform insert!
		$this->model->xhrInsert();
	}
	
	function xhrGetListings(){
		$this->model->xhrGetListings();
	}
	
	function xhrDeleteListing(){
		$this->model->xhrDeleteListing(); 
	}
}