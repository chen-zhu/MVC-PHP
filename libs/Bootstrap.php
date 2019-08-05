<?php

class Bootstrap {
	
	function __construct() {
		//0 => call url/controller, 1 => call method, 3 => arguments
		$url = (string)@$_GET['url'];
		$url = filter_var($url, FILTER_SANITIZE_URL); //security issue!
		$url = explode('/', trim((string)@$_GET['url'], '/'));

		//print_r($url);
		if(empty($url[0])){
			$url = array('index');
		}
		
		//Check if controller file exists or not!
		$file = 'controllers/' . $url[0] . '.php'; 
		if(file_exists($file)){
			require_once $file;
		} else {
			//require_once 'controllers/errors.php';
			//$controller = new Errors();
			//return false;
			$this->errors();
			return false;
		}
		
		//initialize controller.
		$controller = new $url[0];
		$controller->loadModel($url[0]);
		
		//if index is set with params, pass into method.
		if(isset($url[2]) && isset($url[1])){
			if(method_exists($controller, $url[1])){
				$controller->{$url[1]}($url[2]);
			} else {
				$this->errors();
			}
		} elseif(isset($url[1])){
			//if method name is set, call this function under the controller!
			if(method_exists($controller, $url[1])){
				$controller->{$url[1]}();
			} else {
				$this->errors();
			}
		} else {
			$controller->index();		
		}
		
	}
	
	function errors(){
		require 'controllers/errors.php';
		$controller = new Errors();
		$controller->index();
		return false;
	}
	
	
	
}
