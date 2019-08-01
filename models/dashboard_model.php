<?php

//This is where business logic would be placed under.
class dashboard_model extends Model{
	
	function __construct() {
		//echo 'Help model';
		parent::__construct();
	}
	
	function xhrInsert(){ 
		$text = @$_POST['text'];
		$insert = $this->db->insert('data', array('text' => $text));
		$data = array('text' => $text, 'id' => $this->db->get_last_insert_id());
		echo json_encode($data);
	}
	
	function xhrGetListings(){
		$data = $this->db->select('data', array());
		//TODO: Pagination/offset!
		
		echo json_encode($data);
	}
	
	function xhrDeleteListing(){
		$id = @$_POST['id'];
		if($id){
			$del = $this->db->delete('data', array('id' => $id), true);
			//maybe need to check if delete is successful or not!
			echo json_encode(array('success' => true));
		}
	}
}
