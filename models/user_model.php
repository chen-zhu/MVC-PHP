<?php

class user_model extends Model{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function userList($id = NULL){
		if($id){
			//TODO: prepare all db statement in the project!
			$db_query = $this->db->prepare('select id, login, role from users where id = ' . $id);
			$db_query->execute();
			return $db_query->fetch();
		} else {
			$db_query = $this->db->prepare('select id, login, role from users');
			$db_query->execute();
			return $db_query->fetchAll();
		}
	}
	
	public function create($data){
		$db_query = $this->db->prepare('Insert into users (`login`, `password`, `role`) '
				. 'values (:login, :password, :role) ');
		$db_query->execute(array(
			'login' => $data['login'], 
			'password' => $data['password'], 
			'role' => $data['role'], 
		));
		//return $db_query->fetchAll();
	}
	
	public function editSave($data){
		$db_query = $this->db->prepare('Update users SET `login` = :login, `password` = :password, `role` = :role '
				. 'where `id` = :id');
		$db_query->execute(array(
			'login' => $data['login'], 
			'password' => $data['password'], 
			'role' => $data['role'], 
			'id' => $data['id'],
		));
		//return $db_query->fetchAll();
	}
			
	public function delete($id){
		if($id){
			$db_query = $this->db->prepare('Delete from users where id = :id');
			$db_query->execute(array(':id' => $id));
			//echo json_encode(array('success' => true));
		}
	}
	
}
