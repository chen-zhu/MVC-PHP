<?php

class login_model extends Model{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function run(){
		//TODO: create form object in the future!
		$login = $_POST['login'];
		$password = $_POST['password'];		
		
		$data = $this->db->select('users', 
				array(
						'login' => $login, 
						'password' => Hash::create('md5', $password, HASH_KEY),
					), 
				array('id', 'role'))[0]; 
		
		$role = @$data['role'];
		
		if(@$data && $role){
			//login
			Session::init();
			Session::set('role', $role);
			Session::set('loggedIn', true);
			Session::set('userid', @$data['id']);
			header('location: ../dashboard');
		} else {
			header('location: ../login');
		}
		
	}
			
}
