<?php

class note_model extends Model{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function noteList($id = NULL){
		if($id){
			$fetch = $this->db->select('note', 
					array('noteid' => $id, 'userid' => $_SESSION['userid']))[0];
			return $fetch;
		} else {
			$fetch = $this->db->select('note', 
					array('userid' => $_SESSION['userid']));
			return $fetch;
		}
	}
	
	public function create($data){
		$insert = $this->db->insert('note', array(
			'title' => $data['title'], 
			'content' => $data['content'], 
			'userid' => $_SESSION['userid'], 
			//'date_added' => '', 
		));
	}
	
	public function editSave($data){
		$update = $this->db->update('note', 
					array('noteid' => $data['noteid']), 
					array(
						'title' => $data['title'], 
						'content' => $data['content'], 
						'userid' => $_SESSION['userid'],
						//'date_added' => ''
					)
				);
	}
			
	public function delete($id){
		if($id){
			$del = $this->db->delete('note', 
					array('noteid' => $id, 'userid' => $_SESSION['userid']), true);			
		}
	}
	
}
