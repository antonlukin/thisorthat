<?php
/**
* PHP script for providing API handler
*
* @author Anton Lukin
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://lukin.me
**/

require_once realpath(__DIR__ . "/../../config/settings.php");
require_once ABSPATH . $config['paths']['api'];

{
	$api_events = array(
		'GET' => array(
			'_get_items' => '^/items/get/[\d]{1,9}/?',
		),
		'POST' => array(
			'_add_views' => '^/views/add/[\d]{1,9}$',
			'_add_items' => '^/items/add/[\d]{1,9}$',
			'_add_user' => '^/users/add/?$'
		)
	);

	$api = new API($config);

	$query  = $api->strip($_SERVER['REQUEST_URI']);
	$result = $api->process($query, $api_events);
	$output = $api->response($result);

	header('Content-Type: application/json');
	exit($output);
}
