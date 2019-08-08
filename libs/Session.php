<?php


class Session {

	public static function init(){
		@session_start();
	}
	
	public static function set($key, $value){
		$_SESSION[$key] = $value;
	}
	
	public static function get($key){
		return @$_SESSION[$key] ? $_SESSION[$key] : NULL;
	}
	
	public static function destroy(){
		unset($_SESSION);
		session_destroy();
	}
	
	/**
	 * Check if user has permissions/logged in.
	 * 
	 * @param array $allowed_roles Set to array('owner') by default!
	 */
	public static function checkUserLoginStatus($allowed_roles = array('owner')){
		@session_start();
		$logged = $_SESSION['loggedIn'];
		$role = @$_SESSION['role'];
		
		if($logged == false || (!in_array($role, $allowed_roles) && $allowed_roles)){
			@session_destroy();
			header('location: ../login'); //bring users back to login page. 
			exit;
		}
	}
}
