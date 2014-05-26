<?php
final class db_mysqli {
	private $mysqli;
	
	public function __construct($hostname, $username, $password, $database) {
		$this->mysqli = new mysqli($hostname, $username, $password, $database);
		
		if ($this->mysqli->connect_error) {
      		trigger_error('Error: Could not make a database link (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
		}

		$this->mysqli->query("SET NAMES 'utf8'");
		$this->mysqli->query("SET CHARACTER SET utf8");
		$this->mysqli->query("SET CHARACTER_SET_CONNECTION=utf8");
		$this->mysqli->query("SET SQL_MODE = ''");
  }
		
  public function query($sql) {
		$query = $this->mysqli->query($sql);

		if (!$this->mysqli->errno){
			if (isset($query->num_rows)) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;

				unset($data);

				$query->close();

				return $result;
			} else{
				return true;
			}
		} else {
			throw new ErrorException('Error: ' . $this->mysqli->error . '<br />Error No: ' . $this->mysqli->errno . '<br />' . $sql);
		}
	}
	
	public function escape($value) {
		return $this->mysqli->real_escape_string($value);
	}
	
  	public function countAffected() {
    	return $this->mysqli->affected_rows;
  	}

  	public function getLastId() {
    	return $this->mysqli->insert_id;
  	}	
	
	public function __destruct() {
		$this->mysqli->close();
	}
}
