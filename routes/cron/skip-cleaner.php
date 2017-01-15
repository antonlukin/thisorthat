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

	public function get($limit = 50, $offset = 0) {
		try{
			$db = $this->_db;

			$query = "SELECT id, approve 
				FROM item WHERE approve <> 2  
				ORDER BY id ASC 
				LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

			$items = $db->select($query);

			if(empty($items))
				return;

			foreach($items as $item) {
				$query = "SELECT 
					IFNULL(SUM(vote = 'skip'), 0) skip_vote, 
					IFNULL(SUM(vote = 'left'), 0) left_vote, 
					IFNULL(SUM(vote = 'right'), 0) right_vote
					FROM view WHERE item = ? GROUP BY item";

				$answers = $db->select($query, [$item['id']]);
				
				if(empty($answers[0]))
					continue;

				$data = $answers[0];
				$sum = $data['left_vote'] + $data['right_vote'] + $data['skip_vote'];
				$cost = round($data['skip_vote'] / $sum * 100, 2);

				$this->update($item, $cost, $sum);
			}

			$this->get($limit, $offset + $limit);
		}
		catch(DBException $e) {
			echo $e->getMessage();
			exit; 
		}
	} 

	public function update($item, $cost, $sum) {
		echo "{$item['id']}: {$cost} - {$sum}: {$item['approve']}\n";
		// Decline more than 15% skipped questions
		if($cost > 16 && $sum >= 20)
			return $this->hide($item['id'], $cost);

		// Fast decline spam questions
 		if($cost > 50 && $sum > 5)
			return $this->hide($item['id'], $cost); 

		// Exit function if the question already approved
		if($item['approve'] == 1)
			return;

		// Fast approve good questions
 		if($cost == 0 && $sum >= 10)
			return $this->approve($item['id'], $cost); 

		// Auto approve normal questions
		if($cost < 8 && $sum >= 20)
			return $this->approve($item['id'], $cost);

		// Auto approve other questions
		if($cost <= 16 && $sum > 50)
 			return $this->approve($item['id'], $cost); 
	}

 	public function approve($id, $cost) {
 		try{
			$db = $this->_db;

			$query  = "UPDATE item SET approve = 1 WHERE id = " . (int) $id;

 			echo "{$id}: {$cost} / approve\n"; 

			return $db->query($query, []);
		}
		catch(DBException $e) {
			echo $e->getMessage();
			exit;  
		}    	
	} 

	public function hide($id, $cost) {
 		try{
			$db = $this->_db;

			$query  = "UPDATE item SET approve = :approve, reason = :reason WHERE id = " . (int) $id;
			$data = [
				'approve' => 2,
				'reason' => 'Вопрос слишком часто пропускается пользователями'
			];

			echo "{$id}: {$cost} / hide\n";

	  		return $db->query($query, $data);
		}
		catch(DBException $e) {
			echo $e->getMessage();
			exit;   
		}    	
	}

	public function process() {
 		$this->get(); 
	}
}

{
	$cleaner = new Cleaner($config);
	$cleaner->process();
}
