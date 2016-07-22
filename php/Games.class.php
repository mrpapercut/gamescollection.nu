<?php
error_reporting(-1);
class Games {
	public $db;

	public function __construct() {
		$this->db = new DB();
	}

	public function handleRequest($params) {
		switch ($params[1]) {
			case 'all':
				return $this->getAllGames();
				break;
			case 'latest':
				return $this->getLatestGames();
		}
	}

	private function getAllGames() {
		return $this->db->query('SELECT g.id, g.name, g.image, g.release_date, p.abbr AS platform FROM games AS g INNER JOIN platforms AS p WHERE p.id = g.platformId ORDER BY g.id');
	}

	private function getLatestGames() {
		return $this->db->query('SELECT g.id, g.name, g.image, g.release_date, p.abbr AS platform FROM games AS g INNER JOIN platforms AS p WHERE p.id = g.platformId ORDER BY g.id DESC LIMIT 50');
	}
}
