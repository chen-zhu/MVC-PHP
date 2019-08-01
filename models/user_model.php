<?php

class user_model extends Model{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function userList($id = NULL){
		if($id){
			$fetch = $this->db->select('users', array('id' => $id), array('id', 'login', 'role'))[0];
			return $fetch;
		} else {
			$fetch = $this->db->select('users', array(), array('id', 'login', 'role'));
			return $fetch;
		}
	}
	
	public function create($data){
		$insert = $this->db->insert('users', array(
			'login' => $data['login'], 
			'password' => Hash::create('md5', $data['password'], HASH_KEY), 
			'role' => $data['role'], 
		));
	}
	
	public function editSave($data){
		$update = $this->db->update('users', 
					array('id' => $data['id']), 
					array(
						'login' => $data['login'], 
						'password' => Hash::create('md5', $data['password'], HASH_KEY), 
						'role' => $data['role'], 
					)
				);
	}
			
	public function delete($id){
		if($id){
			$del = $this->db->delete('users', array('id' => $id), true);			
		}
	}
	
}
