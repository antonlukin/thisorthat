<?php
/**
* PHP script for providing API handler
*
* @author Anton Lukin
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://lukin.me
**/

require_once realpath(__DIR__ . "/../../config/settings.php");

require_once ABSPATH . $config['paths']['hint'];   
require_once ABSPATH . $config['paths']['utf8'];   
require_once ABSPATH . $config['paths']['censure'];  
require_once ABSPATH . $config['paths']['core']; 

class API {

	protected $_core = null;
	protected $_conf = null; 

	function __construct($config) {
		$this->_conf = $config;
		$this->_core = new Core($config);
	}

	private function _add_error($atts) {
		throw new Exception("Невозможно добавить вопрос. Обновите приложение", 500);
	}             

	/**
	 * Request: /items/get/[:count]
	 * Method: GET
	 * Answer: [item_id] => array('left_text' => %s, 'right_text' => %s, 'left_count' => %i, 'right_count' => %i, 'moderate' => %i)
	**/
	private function _get_items($atts) {
		$_ = $this->_core;

		$user = $this->authorization(false);

		if(!isset($atts[2]))
			return $_->get_items($user);

		if($count = $_->attribute($atts, 2, '^[\d]{0,3}$'))
			return $_->get_items($user, $count);

		throw new Exception("Wrong count value format", 400);
	}

 	/**
	 * Request: /items/self
	 * Method: GET
	 * Answer: [items] => %array
	**/
	private function _self_items($atts) {
		$_ = $this->_core;

		$user = $this->authorization();

		if($items = $_->self_items($user))
			return $items;

		throw new Exception("Something went wrong", 400);
	} 

 	/**
	 * Request: /items/show/:ids
	 * Data: [ids] => %s [comma-separated ids | <= 20]
	 * Method: GET
	 * Answer: [%i] => array('left_text' => %s, 'right_text' => %s, 'left_count' => %i, 'right_count' => %i, 'status' => %i)
	**/
	private function _show_items($atts) {
		$_ = $this->_core;

		$user = $this->authorization(false);

		if(!isset($atts[2]))
			throw new Exception("Items id do not match", 400); 

		if(!$items = $_->attribute($atts, 2, '^[\d,]+$'))
			throw new Exception("Wrong items id format", 400); 
		
		return $_->show_items($user, $items);
	} 

	/**
	 * Request: /items/add/
	 * Method: POST
	 * Data: [items] => %array
	 * Answer: [status] => %i, [description] => %s
	**/
	private function _add_items($atts) {
		$_ = $this->_core;

		$raw = $_->dataset();

		if(!$items = $_->attribute($raw, 'items', 'array', true))
			throw new Exception("Items array required", 400);

		$user = $this->authorization();

		if(!$return = $_->add_items($user, $items))
			throw new Exception("Items array wrong format", 400);

		return array('items' => $return); 
	}

 
	/**
	 * Request: /users/add/
	 * Method: POST
	 * Data: [client] => %s, [unique] => %s
	 * Answer: [user] => %i, [token] => %s
	**/
	private function _add_user($atts) {
		$_ = $this->_core;

		$raw = $_->dataset();

		if(!$source = $_->attribute($raw, 'client', '^[a-z0-9-_]{0,16}$'))
			throw new Exception("Client value required", 400);

 		if(!$unique = $_->attribute($raw, 'unique', '^[a-z0-9-_]{0,64}$'))
			throw new Exception("Unique value required", 400);

		return $_->register($source, $unique);
	}

	/**
	 * Request: /views/add/
	 * Method: POST
	 * Data: [views] => %array
	 * Answer: [status] => %i, [description] => %s
	**/
	private function _add_views($atts) {
		$_ = $this->_core;

		$raw = $_->dataset();

		if(!$views = $_->attribute($raw, 'views', 'array', true))
			throw new Exception("Views array required", 400);

		$user = $this->authorization();

		if(!$_->add_views($user, $views))
			throw new Exception("Views array required", 400);

		return $this->success("Completed successfully", 202);
	}

	protected function authorization($required = true) {
		$_ = $this->_core;

		if(!isset($_SERVER['HTTP_AUTHORIZATION'])) :
			if($required)
				throw new Exception("Authorization required", 401);
			else
				return false;
		endif;

		if(!preg_match('~^Basic\s+?([a-z0-9=]+)$~i', $_SERVER['HTTP_AUTHORIZATION'], $auth))
			throw new Exception("Wrong authorization format", 401);

		list($user, $password) = explode(':' , base64_decode($auth[1]));

		if(!$_->authenticate($user, $password))
			throw new Exception("Authorization failed", 401);

		return $user;
	}

	public function process($request, $events) {
		$http = strtoupper($_SERVER['REQUEST_METHOD']);

		foreach($events[$http] as $func => $match) {
			if(!preg_match("~{$match}~i", $request))
				continue;

			$method = $func;
			break;
		}

		if(!isset($method) || !method_exists($this, $method))
			return $this->error("Method does not exist", 400);

		$atts = explode("/", trim($request, "/"));

		try{
			$result = $this->$method($atts);
		}
 		catch(CoreException $e){
			$description = $e->getDescription();

			$result = $this->error($description, 500);
		}
		catch(Exception $e){
			$result = $this->error($e->getMessage(), $e->getCode());
		}

		return $result;
	}

	public function strip($request) {
		$api_path = $this->_conf['urls']['api'];

		if(substr($request, 0, strlen($api_path)) == $api_path)
			return substr($request, strlen($api_path));

		return $request;
	}

	public function error($description, $code) {
		if(function_exists('http_response_code'))
			http_response_code($code);

		return array("error" => $code, "description" => $description);
	}

	public function success($description, $code) {
		if(function_exists('http_response_code'))
			http_response_code($code);

		return array("success" => $code, "description" => $description);
	}

	public function response($result) {
		return json_encode($result);
	}
} 


{
	$api_events = array(
		'GET' => array(
			'_get_items' => '^/items/get/?[\d]*/?',
 			'_show_items' => '^/items/show/[\d,]+/?',
  			'_self_items' => '^/items/self/?',
		),
		'POST' => array(
			'_add_views' => '^/views/add/?$',
			'_add_items' => '^/items/add/?$',
			'_add_user' => '^/users/add/?$',
 			'_add_error' => '^/items/error/?$' 
		)
	);

	$api = new API($config);

	$query  = $api->strip($_SERVER['REQUEST_URI']);
	$result = $api->process($query, $api_events);
	$output = $api->response($result);

	header('Content-Type: application/json');
	exit($output);
}
