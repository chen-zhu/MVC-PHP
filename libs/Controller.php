<?php

/**
 * 1. Make view accessible via controller
 * 2. Base Controller. Every controller must extends from this one!
 * 
 */

class Controller {
	
	function __construct() {
		//echo '<br>Inside Main Controller<br>';
		$this->view = new View();//Make view accessible via controler!
	}
	
	public function loadModel($name){
		$path = 'models/' . $name . '_model.php';
		if(file_exists($path)){
			require $path;
			$model_name = $name . '_model';
			//initialize model class directly and save it as public object in Conroller!
			$this->model = new $model_name;
			
			//TODO: add error handling here in case model_name cannot be created!!
		}
	}
	
}
