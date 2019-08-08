<?php

class Bootstrap {
	
	private $url = NULL;
	private $controller = NULL;
	
	function __construct() {
		$this->getURL();
			
		$this->loadController();
		
		$this->callControllerMethod();
	}
	
	private function getURL(){
		//0 => call url/controller, 1 => call method, 3 => arguments
		$url = (string)@$_GET['url'];
		$url = filter_var($url, FILTER_SANITIZE_URL); //security issue!
		$url = explode('/', trim((string)@$_GET['url'], '/'));

		//If blank, set to index by default!
		if(empty($url[0])){
			$url = array('index');
		}
		
		$this->url = $url;
		return $url;
	}
	
	private function loadController(){
		//Check if controller file exists or not!
		$file = 'controllers/' . $this->url[0] . '.php'; 
		if(file_exists($file)){
			require_once $file;
		} else {
			$this->errors();
			return false;
		}
		
		//initialize controller & call Model!
		$controller = new $this->url[0];
		$controller->loadModel($this->url[0]);
		
		$this->controller = $controller;
	}
	
	private function callControllerMethod(){
		//Stop any action if no controller is set!
		$controller = $this->controller;
		$count = count($this->url);
		
		if($controller === NULL || $count < 1){
			return false;
		}
		
		// http://localhost/controller/(method/(param/(param/(param))))
		// $url[0] = Controller
		// $url[1] = Method
		// $url[2] = param
		// $url[3] = param
		// $url[4] = param
		
		if($count > 1){
			if(!method_exists($controller, $this->url[1])){
				$this->errors();
				return;
			}
		}
		
		switch ($count){
			case 5:
				$controller->{$this->url[1]}(array($this->url[2], $this->url[3], $this->url[4]));
				break;
			case 4:
				$controller->{$this->url[1]}(array($this->url[2], $this->url[3]));
				break;
			case 3: 
				$controller->{$this->url[1]}(array($this->url[2]));
				break;
			case 2: 
				$controller->{$this->url[1]}();
				break;
			
			default:
				//default to index page!
				$controller->index();
				//$this->errors(); 
				//exit('Please check your Bootstrap.');
				break;
		}
		
	}
	
	private function errors(){
		require 'controllers/errors.php';
		$controller = new Errors();
		$controller->index();
		return false;
	}
	
}
