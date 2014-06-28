<?php
/**
* PHP Class describes extend API methods
*
* @author Anton Lukin
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://lukin.me
**/
      

if (!class_exists('core')) 
	require_once ABSPATH . $config['paths']['core'];

class API {

	protected $_core = null;

	function __construct($config) {
		$this->_core = new Core($config);
	}

	/**
	 * Request: /items/get/[:user]/[:count]
	 * Method: GET
	 * Answer: [item_id] => array('left_text' => %s, 'right_text' => %s, 'left_count' => %i, 'right_count' => %i)
	**/
	private function _get_items($atts) {
		$_ = $this->_core;

		if(!isset($atts[2]))
			return $_->get_items();

		if(!$user = $_->attribute($atts, 2, '^[\d]{0,9}$'))
			throw new Exception("User id does not match", 400);

		$this->authorization($user);
		
		if(!isset($atts[3]))
			return $_->get_items($user); 

		if($count = $_->attribute($atts, 3, '^[\d]{0,3}$'))
			return $_->get_items($user, $count); 

		throw new Exception("Wrong count value format", 400); 
	}

	/**
	 * Request: /users/add/
	 * Method: POST
	 * Data: :client => %s, :unique => %s
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
	 * Request: /vote/add/[:user]
	 * Method: POST
	 * Data: :views => %array
	 * Answer: [user] => %i, [token] => %s
	**/
	private function _add_views($atts) {
		$_ = $this->_core;

		$raw = $_->dataset();

		if(!$user = $_->attribute($atts, 2, '^[\d]{0,9}$'))
			throw new Exception("User id does not match", 400);  
		
		if(!$views = $_->attribute($raw, 'views', 'array', true)) 
			throw new Exception("Views array required", 400);  

		$this->authorization($user);  

		return $_->add_views($user, $views);
	}             
                                              
	protected function authorization($user) {
		$_ = $this->_core; 

		if(!isset($_SERVER['HTTP_AUTHORIZATION']))
			throw new Exception("Authorization required", 401); 
		
		if(!preg_match('~^Token\s+?([a-f0-9]{32})$~i', $_SERVER['HTTP_AUTHORIZATION'], $token))
			throw new Exception("Wrong authorization format", 401);  
 
//		$token[0] = '5d41402abc4b2a76b9719d911017c592';
		if(!$_->authenticate($user, $token[1]))
			throw new Exception("Token mismatch user id", 401);   
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
			return $this->error(400, "Method does not exist");

		$atts = explode("/", trim($request, "/"));

		try{
			$result = $this->$method($atts);
		}
		catch(Exception $e){
			$result = $this->error($e->getCode(), $e->getMessage());
		}

		return $result;
	}

	public function error($code, $description) {
		return array("error" => $code, "description" => $description);
	}

	public function response($result) {
		return $result;
		return json_encode($result);
	}
}
