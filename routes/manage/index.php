<?php
/**
* PHP script for providing Admin panel
*
* @author Anton Lukin
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://lukin.me
**/

require_once realpath(__DIR__ . "/../../config/settings.php");

if (!class_exists('db'))
	require_once ABSPATH . $config['paths']['db']; 

class Admin {

	protected $_db = null;

	function __construct($config) {
		$this->_db = new DB($config['db']);
	} 

	public function get_new_items($count = 10) {
		try{
			$db = $this->_db;

			$query = "SELECT id, user, left_text, right_text FROM item WHERE approve = 0 LIMIT " . (int)$count;

			$items = $db->select($query); 
		}
		catch(DBException $e) {
			throw new CoreException($e->getMessage(), 0);
		}       

		return $items;
	}

 	public function get_count() {
		try{
			$db = $this->_db;

			$query = "SELECT count(id) as c  FROM item WHERE approve = 0";

			$count = $db->select($query); 
			$count = $count[0]['c'];
		}
		catch(DBException $e) {
			throw new CoreException($e->getMessage(), 0);
		}       

		return $count;
	} 

	public function delete_item($id) {
 		try{
			$db = $this->_db;

			$query  = "DELETE FROM item WHERE id = " . (int) $id;

			return $db->query($query, $data);
		}
		catch(DBException $e) {
			$this->json_die(array('error' => true));
		}    	
	}

 	public function update_status($data, $id) {
		try{
			$db = $this->_db;

			$query  = "UPDATE item SET left_text = :left_text, right_text = :right_text, approve = :approve WHERE id = " . (int) $id;

			return $db->query($query, $data);
		}
		catch(DBException $e) {
			$this->json_die(array('error' => true));
		}
	}     

	public function json_die($data) {
		$json = json_encode($data);
 
		header('Content-Type: application/json');
		die($json); 
	}

	public function moderate() {
		$item = (int)$_POST['item'];
		$data = array(
			'approve' => (int)$_POST['status'],
			'left_text' => $_POST['left_text'],
			'right_text' => $_POST['right_text']
		);

		if($data['approve'] === 1 || $data['approve'] === 2) {
			if($this->update_status($data, $item))
				$this->json_die(array('success' => true));
		}

 		if($data['approve'] === 3) {
 			if($this->delete_item($item))
				$this->json_die(array('success' => true));
		}
			
		$this->json_die(array('error' => true));   
	}

	public function process() {
		if(count($_POST) < 1)
			return require __DIR__ . '/templates/main.php';

		if(isset($_POST['path']) && $_POST['path'] == 'moderate')
			$this->moderate();
	}
}

{
	$admin = new Admin($config);
	$admin->process();
}
