<?php

error_reporting(-1);
class Api {
	public $games;

	public function autoloader ($class) {
        require_once($class.'.class.php');
    }

    public function __construct() {
        if(!isset($_SESSION)) session_start();
        require_once('definitions.inc.php');

        spl_autoload_register('Api::autoloader');

	}

	public function processRequest() {
		$params = array_filter(explode('/', $_GET['request']));

		header('Content-Type: application/json');
		return $this->handleParams($params);
	}

	private function handleParams($params) {
		switch ($params[0]) {
			case 'games':
				$this->games = new Games();
				$res = $this->games->handleRequest($params);
		}

		return $res;
	}
}

if (isset($_GET['request'])) {
	$api = new Api();
	$res = json_encode($api->processRequest());
	if (!json_last_error()) {
		echo $res;
	} else {
		echo json_last_error_msg();
	}
}
