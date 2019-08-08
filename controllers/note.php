<?php

//index controler

class Note extends Controller{
	
	public function __construct(){
		parent::__construct();
		
		//Session::checkUserLoginStatus(array('owner'));
		Session::checkUserLoginStatus(array());
	}
	
	public function index(){
		$this->view->noteList = $this->model->noteList();
		$this->view->render('note/index');
	}
	
	public function create(){
		$data = array(
			'title' => $_POST['title'],
			'content' => $_POST['content'], 
		);
		
		$this->model->create($data);
		
		//auto refresh the page without asking js to get involved (ex. append html)
		header('location: ' . URL . 'note');
	}
	
	//bring to a new edit page instead!
	public function edit($id){
		//1. fetch the note
		$this->view->note = $this->model->noteList($id);
		$this->view->render('note/edit');
	}
	
	public function editSave($id){
		$data = array(
			'noteid' => $id,
			'title' => $_POST['title'],
			'content' => $_POST['content'], 
		);
		
		$this->model->editSave($data);
		
		header('location: ' . URL . 'note');
	}
	
	public function delete($id){
		$this->model->delete($id);
		
		//auto refresh
		header('location: ' . URL . 'note');
	}
	
}