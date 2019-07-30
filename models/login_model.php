<?php

class login_model extends Model{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function run(){
		//TODO: create form object in the future!
		$login = $_POST['login'];
		$password = $_POST['password'];		
		$db_query = $this->db->prepare("select id, role from users "
				. "where login = :login "
					. "and password = MD5(:password) ");
		$db_query->execute(array(
			':login' => $login, 
			':password' => $password,
		));
		
		$data = $db_query->fetch();
		$role = $data['role'];
		
		$count = $db_query->rowCount();
		if($count > 0){
			//login
			Session::init();
			Session::set('role', $role);
			Session::set('loggedIn', true);
			header('location: ../dashboard');
		} else {
			header('location: ../login');
		}
		
	}
			
}
