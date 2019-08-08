<?php

//index controler

class User extends Controller{
	
	public function __construct(){
		parent::__construct();
		Session::checkUserLoginStatus(array('owner'));
		/*Session::init();
		$logged = Session::get('loggedIn');
		$role = Session::get('role'); //also check permissions here!
		
		if($logged == false || $role != 'owner'){
			Session::destroy();//in case.
			header('location: ../login'); //bring users back to login page. 
			exit;
		}*/
		
	}
	
	public function index(){
		$this->view->userList = $this->model->userList();
		$this->view->render('user/index');
	}
	
	public function create(){
		$data = array(
		'login' => $_POST['login'],
		'password' => $_POST['password'], 
		'role' => $_POST['role'], 
		);
		
		$this->model->create($data);
		
		//auto refresh the page without asking js to get involved (ex. append html)
		header('location: ' . URL . 'user');
	}
	
	//bring to a new edit page instead!
	public function edit($id){
		//1. fetch the user
		$this->view->user = $this->model->userList($id);
		$this->view->render('user/edit');
	}
	
	public function editSave($id){
		$data = array(
		'id' => $id,
		'login' => $_POST['login'],
		'password' => $_POST['password'], 
		'role' => $_POST['role'], 
		);
		
		$this->model->editSave($data);
		
		header('location: ' . URL . 'user');
	}
	
	public function delete($id){
		$this->model->delete($id);
		
		//auto refresh
		header('location: ' . URL . 'user');
	}
	
}