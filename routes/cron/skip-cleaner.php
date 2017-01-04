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

class Cleaner {
	protected $_db = null;

	function __construct($config) {
		$this->_db = new DB($config['db']);
	} 

	public function get_items($approve, $allowed, $limit = 50, $offset = 0) {
		try{
			$db = $this->_db;

			$query = "SELECT id FROM item WHERE approve = ? LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
			$items = $db->select($query, [$approve]);

			if(empty($items))
				return;

			foreach($items as $item) {
				$query = "SELECT IFNULL(SUM(vote = 'skip'), 0) skip FROM view WHERE item = ? GROUP BY item";
				$count = $db->select($query, [$item['id']]);
				
				if(empty($count))
					continue;

				$skip = $count[0]['skip'];

				if($skip <= $allowed)
					continue;
			
				if($this->hide_item($item['id']))
					echo "deleted: {$item['id']} - $skip\n";
			}

			$this->get_items($approve, $allowed, $limit, $offset + $limit);
		}
		catch(DBException $e) {
			echo $e->getMessage();
			exit; 
		}
	} 

	public function hide_item($id) {
 		try{
			$db = $this->_db;

			$query  = "UPDATE item SET approve = :approve, reason = :reason WHERE id = " . (int) $id;
			$data = [
				'approve' => 2,
				'reason' => 'Вопрос слишком часто пропускается пользователями'
			];

			return $db->query($query, $data);
		}
		catch(DBException $e) {
			$this->json_die(array('error' => true));
		}    	
	}

	public function process() {
		$this->get_items(1, 1500);
 		$this->get_items(0, 50); 
	}
}

{
	$cleaner = new Cleaner($config);
	$cleaner->process();
}
