<?php

class Bootstrap {
	
	function __construct() {
		//0 => call url/controller, 1 => call method, 3 => arguments
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
			require_once 'controllers/errors.php';
			$controller = new Errors();
			return false;
		}
		
		//initialize controller.
		$controller = new $url[0];

		//if index is set with params, pass into method.
		if(isset($url[2]) && isset($url[1])){
			if(method_exists($controller, $url[1])){
				$controller->{$url[1]}($url[2]);
			} else {
				echo 'err';
			}
		} elseif(isset($url[1])){
			//if method name is set, call this function under the controller!
			if(method_exists($controller, $url[1])){
				$controller->{$url[1]}();
			} else {
				echo 'errrr';
			}
		} else {
			$controller->index();		
		}
		
	}
	
	
	
}
