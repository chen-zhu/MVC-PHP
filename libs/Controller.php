<?php

/**
 * 1. Make view accessible via controller
 * 2. Base Controller. Every controller must extends from this one!
 * 
 */

class Controller {
	
	function __construct() {
		echo '<br>Inside Main Controller<br>';
		$this->view = new View();//Make view accessible via controler!
	}
	
}
