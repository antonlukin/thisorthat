<?php
/**
* PHP Class for providing basic service functionality
*
* @author Anton Lukin
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://lukin.me
**/

	
class Core {

	protected $_db = null;

	function __construct($config) {
		$this->_db = new DB($config['db']); 

		$this->init();
	}

	private function _normilize_array($in, $out = array()) {
		foreach($in as $k => $v) {
			$id = array_shift($v);
			$out[$id] = $v;
		}

		return $out;
	}

	private function _set_viewed($user, $items) {
		$data = array();

		try{
			$db = $this->_db;

			$ids = array_keys($items);

			foreach($ids as $id)
				$data[] = array(':item' => $id, ':user' => (int)$user);

			$query = "INSERT IGNORE INTO view (user, item) VALUES (:user, :item)";
	
			return $db->multiple($query, $data);
    	}                                           
		catch(Exception $e) {
			return false;
		}    
	}

	private function _set_voated($user, $items) {
		try{
			$db = $this->_db;   

			$ids = array_keys($items);

 			foreach($ids as $id)
				$data[] = array(':item' => $id, ':user' => (int)$user, ':vote' => 'left');        

			$query  = "INSERT INTO view (user, item, vote) VALUES (:user, :item, :vote) ON DUPLICATE KEY UPDATE vote = :vote;";
 			$query .= "UPDATE item SET left_vote = left_vote + 1 WHERE id = :item;"; 

			return $db->multiple($query, $data);
    	}                                           
		catch(Exception $e) {
			return false;
		}    

		return true;	
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

	public function items($user = FALSE, $count = 10) {
		if(FALSE === $user)
			return $this->_get_random_items($count);

		return $this->_get_user_items($user, $count);
	}

	public function view() {

	}

	public function vote() {

	}

	public function init() {



	} 
}
