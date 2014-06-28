<?php
/**
* PHP Class for providing basic service functionality
*
* @author Anton Lukin
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://lukin.me
**/

if (!class_exists('db')) 
	require_once ABSPATH . $config['paths']['db'];
	
class Core {

	protected $_db = null;

	function __construct($config) {
		$this->_db = new DB($config['db']); 
	}

	private function _normilize_array($in, $out = array()) {
		foreach($in as $k => $v) {
			$id = array_shift($v);
			$out[$id] = $v;
		}

		return $out;
	}

	private function _set_viewed($user, $items) {
		try{
			$db = $this->_db;   

 			foreach($items as $id => $vote)
				$data[] = array('item' => (int)$id, 'user' => (int)$user, 'vote' => $vote);        

			$query  = "INSERT INTO view (user, item, vote) VALUES (:user, :item, :vote) ON DUPLICATE KEY UPDATE vote = :vote;";
 			$query .= "UPDATE item SET left_vote = left_vote + 1 WHERE id = :item;"; 

			return $db->multiple($query, $data);
		}                                           
		catch(Exception $e) {
			return false;
		}    
	}

	private function _get_random_items($count) {
		try{
			$db = $this->_db;

			$query = "SELECT id, left_text, right_text, left_vote, right_vote FROM item ORDER BY RAND() LIMIT " . (int)$count;
			$items = $db->select($query);
		}                                           
		catch(Exception $e) {
			return false;
		}

		return $this->_normilize_array($items);
	}

 	private function _get_user_items($user, $count) {
		try{
			$db = $this->_db;

			$query = "SELECT item.id, left_text, right_text, left_vote, right_vote FROM item LEFT JOIN view ON item.id = view.item WHERE view.user <> ? OR view.item IS NULL ORDER BY RAND() LIMIT " . (int)$count;
			$items = $db->select($query, array((int)$user));
		}                                           
		catch(Exception $e) {
			return false;
		}

		return $this->_normilize_array($items);       
	} 

	private function _add_new_user($data) {
		try{
			$db = $this->_db;

			$query = "INSERT INTO user (secret, client, `unique`) VALUES (:secret, :client, :unique)";

			return $db->lastid($query, $data);
		}                                           
		catch(Exception $e) {
			return false;
		}
	}           

	private function _check_user_token($user, $secret) {
		try{
			$db = $this->_db;

			$query = "SELECT id FROM user WHERE id = ? AND secret = ? LIMIT 1";
			$count = $db->num_rows($query, array((int)$user, $secret));
		}                                           
		catch(Exception $e) {
			return false;
		} 

		return $count > 0;
	}

	private function _get_secret($token) {
		return substr(hash('sha256', $token), 10, 32);
	}

	public function get_items($user = false, $count = 10) {
		if(false === $user)
			return $this->_get_random_items($count);

		return $this->_get_user_items($user, $count);
	}

	public function add_views($user, $data) {
        $valid = array('left', 'right');

		foreach($data as $id => $vote)
			if(!in_array($vote, $valid))
				unset($data[$id]);

 		return $this->_set_viewed($user, $data);    	
	}         

	public function authenticate($user, $token) {
		$secret = $this->_get_secret($token);

		return $this->_check_user_token($user, $secret);
	}

	public function register($client, $unique) {
		$token = md5(uniqid(rand(), true)); 

		$data = array (
			'secret' => $this->_get_secret($token),
			'unique' => $unique,
			'client' => $client
		);

		$user = $this->_add_new_user($data);

		return array('user' => $user, 'token' => $token);
	}

	public function attribute($list, $offset, $regex, $type = false) {
		if(!isset($list[$offset]))
			return false;

		if(true === $type && gettype($list[$offset]) !== $regex)
			return false;

		if(true === $type)
			return $list[$offset]; 

		if(!preg_match("/{$regex}/i", $list[$offset]))
			return false;

		return $list[$offset];
	}  

	public function dataset() {
		$raw = file_get_contents("php://input");

		return json_decode($raw, true);
	}
}
