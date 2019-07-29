<?php

//This is where business logic would be placed under.
class dashboard_model extends Model{
	
	function __construct() {
		//echo 'Help model';
		parent::__construct();
	}
	
	function xhrInsert(){ 
		$text = @$_POST['text'];
		$db_query = $this->db->prepare('insert into data (text) values (:text)');
		$db_query->execute(array(':text' => $text));
		
		$data = array('text' => $text, 'id' => $this->db->lastInsertId());
		echo json_encode($data);
	}
	
	function xhrGetListings(){
		$db_query = $this->db->prepare('select * from data');
		$db_query->execute();
		echo json_encode($db_query->fetchAll());
	}
	
	function xhrDeleteListing(){
		$id = @$_POST['id'];
		if($id){
			$db_query = $this->db->prepare('Delete from data where id = "' . $id . '"');
			$db_query->execute();
			echo json_encode(array('success' => true));
		}
	}
}
