<?php

/**
 * Description of View
 *
 * @author stone
 */
class View {
	function __construct() {
		//echo '<br>This is View<br>';
	}
	
	//render the page and call views function to complete UI.
	public function render($name, $includeHeaderFooter = true) {
		if($includeHeaderFooter){
			require 'views/header.php';
		}
		require 'views/' . $name . '.php';
		
		if($includeHeaderFooter){
			require 'views/footer.php';
		}
	}
}