<?php 

//TODO: Change ini file instead!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config/paths.php';
require 'config/database.php';
require 'config/constants.php';

function __autoload($class){
	require "libs/$class.php";//use require. halt the script if not found!
}

//require 'libs/Bootstrap.php';
//require 'libs/Controller.php';
//require 'libs/View.php';
//require 'libs/Model.php';
//require 'libs/Database.php';
//require 'libs/Session.php';
//require 'libs/Hash.php';

$app = new Bootstrap();

?>