<?php

/**
 * Description of View
 *
 * @author stone
 */
class View {
	function __construct() {
		echo '<br>This is View<br>';
	}
	
	//render the page and call views function to complete UI.
	public function render($name) {
		require 'views/' . $name . '.php';
	}
}