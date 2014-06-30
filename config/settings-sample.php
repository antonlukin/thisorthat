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
			"baseUrl" => "http://example.org"
		),
		"paths" => array(
			"api" => "/application/classes/api.class.php",
			"core" => "/application/classes/core.class.php",
 			"db" => "/application/classes/db.class.php",
		)
	);

	defined("ABSPATH")
		or define("ABSPATH", realpath(dirname(__FILE__) . "/../"));

	ini_set("error_reporting", "true");
	error_reporting(E_ALL|E_STRICT);
}
