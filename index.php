<?php 

//TODO: Change ini file instead!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

function autoload($class){
	require_once "libs/$class.php";//use require. halt the script if not found!
}

spl_autoload_register('autoload');

$app = new Bootstrap();

?>