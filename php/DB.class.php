<?php

class DB {
	private $db_user;
	private $db_pass;
	private $db_database;

	public $conn;

	public function __construct() {
		$this->db_user = DB_USER;
		$this->db_pass = DB_PASS;
		$this->db_database = DB_DATABASE;
		$this->db_host = DB_HOST;

		$this->conn = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_database);

	}

	public function query($query = null) {
		$rows = array();

		if (is_null($query)) {
			die('No query given!');
		} else {
			if (!$result = $this->conn->query($query)) {
				return 'Error processing query <strong>"'.$query.'"</strong>';
			} elseif($result->num_rows == 0){
				return false;
			} else {
				while($row = $result->fetch_assoc()) {
					$rows[] = $row;
				}

				return $rows;
			}
		}
	}

	public function insert($table, $data = array()) {
		$query = 'INSERT INTO '.$table.' SET ';
		foreach($data as $column => $value){
			if (!is_null($value)) $query .= $column.' = "'.mysqli_real_escape_string($this->conn, $value).'", ';
		}

		if (!$result = $this->conn->query(substr($query, 0, -2))) {
			return 'Error inserting row with query <strong>"'.$query.'"</strong><br>';
		} else {
			return true;
		}
	}

	public function update($table, $id, $data = array()) {
		$query = 'UPDATE '.$table.' SET ';
		foreach($data as $column => $value){
			if (!is_null($value)) $query .= $column.' = "'.mysqli_real_escape_string($this->conn, $value).'", ';
		}

		$query = substr($query, 0, -2);
		$query .= ' WHERE id = '.$id;

		if (!$result = $this->conn->query($query)) {
			return 'Error updating row with query <strong>"'.$query.'"</strong><br>';
		} else {
			return true;
		}
	}

	public function delete($table, $id) {
		$query = 'DELETE FROM '.$table.' WHERE id = '.$id;

		if (!$result = $this->conn->query($query)) {
			return 'Error deleting row with query <strong>"'.$query.'"</strong><br>';
		} else {
			return true;
		}
	}

	public function id_exists($table, $id) {
		$query = 'SELECT id FROM '.$table.' WHERE id = '.$id;

		if (!$result = $this->query($query)) {
			return false;
		} else {
			return true;
		}
	}

	public function mysqli_escape($value) {
		return mysqli_real_escape_string($this->conn, $value);
	}
}