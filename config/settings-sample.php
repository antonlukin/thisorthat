<?php
{
	$config = array(
		"db" => array(
			"driver" => "mysql",
			"dbname" => "wyr",
			"username" => "wyr",
			"password" => "wyr",
			"host" => "localhost"
		),
		"urls" => array(
			"https" => "https://thisorthat.ru",
 			"http" => "http://thisorthat.ru", 
			"api" => "/api"
		), 
		"paths" => array(
			"core" => "/application/classes/core.class.php",
 			"db" => "/application/classes/db.class.php",
   			"hint" => "/application/classes/hint.class.php", 
  			"utf8" => "/application/classes/utf8.class.php", 
  			"censure" => "/application/classes/censure.class.php" 
		)
	);

	defined("ABSPATH")
		or define("ABSPATH", realpath(dirname(__FILE__) . "/../"));

	ini_set("error_reporting", "true");
	error_reporting(E_ALL|E_STRICT);
}
