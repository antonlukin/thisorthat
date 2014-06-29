<?php
/**
* Simple database management based on PDO
*
* @author Anton Lukin
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://lukin.me
**/


class DB {

	private $_dbh = null;

	function __construct($config){
		try{
			$link = new PDO(
				$config['driver'] . ":host=" . $config['host'] . ";dbname=" . $config['dbname'],
				$config['username'], $config['password'],
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
			);

			$link->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			throw new DBException("connect");
		}

		$this->_dbh = $link;
	}

	function __destruct(){
		$this->_dbh = null;
	}

	public function select($query, $data = false, $result = array()){
		$link = $this->_dbh;

		try{
			if(!$data)
				$query = $link->query($query);

			else {
				$query = $link->prepare($query);
				$query->execute($data);
			}

			$query->setFetchMode(PDO::FETCH_ASSOC);

			while($row = $query->fetch())
				$result[] = $row;
		}
		catch(PDOException $e){
			throw new DBException("select");
		}

		return $result;
	}

	public function multiple($query, $data = array()){
		$link = $this->_dbh;

		try{
			$link->beginTransaction();

			$prepare = $link->prepare($query);

			foreach($data as $params) {
				$prepare->execute($params);
				$prepare->closeCursor();
			}

			$link->commit();
		}
		catch(PDOException $e) {
			$link->rollBack();

			throw new DBException("multiple");
		}

		return true;
	}

	public function query($query, $data = array()){
		$link = $this->_dbh;

		try{
			$link->beginTransaction();

			$prepare = $link->prepare($query);
			$prepare->execute($data);

			$link->commit();
		}
		catch(PDOException $e) {
			$link->rollBack();

			throw new DBException("query");
		}

		return true;
	}

	public function lastid($query, $data = array()){
		$link = $this->_dbh;

		try{
			$link->beginTransaction();

			$prepare = $link->prepare($query);
			$prepare->execute($data);

			$id = $link->lastInsertId();
			$link->commit();

			return $id;
		}
		catch(PDOException $e) {
			$link->rollBack();

			throw new DBException("lastid");
		}

		return true;
	}

	public function num_rows($query, $data = array()){
		$link = $this->_dbh;

		$result = $link->prepare($query);
		$result->execute($data);

		return $result->fetchColumn();
	}
}

class DBException extends Exception{}
