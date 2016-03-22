<?php

require_once('definitions.inc.php');
require_once('DB.class.php');

class Update {
	public $platforms = null;
	public $gamelist = null;
	private $db;

	public function __construct() {
		$this->db = new DB();
	}

	public function handleUpload() {
		$zip = new ZipArchive;
		if ($zip->open($_FILES['file']['tmp_name']) === true) {
			$file = json_decode($zip->getFromIndex(0));
			$zip->close();

			if ($file) {
				$this->gamelist = $this->parseGamelist($file->backup->Game);
				$this->platforms = $this->parsePlatforms($file->backup->Platform);
			}
		}

		$res = array();
		if (count($this->gamelist) > 0) {
			$res['gamelist'] = count($this->gamelist);
		}
		if (count($this->platforms) > 0) {
			$res['platforms'] = count($this->platforms);
		}

		return json_encode($res);
	}

	private function parseDate($date) {
		preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})/', $date, $matches);

		return mktime(0, 0, 0, $matches[3], $matches[2], $matches[1]) * 1000;
	}

	private function gameExists($id) {
		return is_array($this->db->query("SELECT * FROM games WHERE id = ".$id));
	}

	private function parseGamelist($list) {
		$games = array();

		foreach($list as $game) {
			if (!isset($game->is_wishlist_item)) {
				if (!$this->gameExists($game->id)) {
					array_push($games, array(
						'id' => $game->id,
						'name' => $game->name,
						'platformId' => $game->platform_id,
						'image' => json_encode(array(
							'large' => isset($game->image_url_large) ? $game->image_url_large : null,
							'medium' => isset($game->image_url_medium) ? $game->image_url_medium : null,
							'small' => isset($game->image_url_small) ? $game->image_url_small : null,
							'thumb' => isset($game->image_url_thumb) ? $game->image_url_thumb : null
						)),
						'description' => isset($game->description_short) ? $game->description_short : null,
						'release_date' => isset($game->release_date) ? $this->parseDate($game->release_date) : null
					));
				}
			}
		}

		return $games;
	}

	private function platformExists($id) {
		return is_array($this->db->query("SELECT * FROM platforms WHERE id = ".$id));
	}

	private function parsePlatforms($list) {
		$platforms = array();

		foreach($list as $platform) {
			if (!$this->platformExists($platform->id)) {
				array_push($platforms, array(
					'id' => $platform->id,
					'name' => $platform->name
				));
			}
		}

		return $platforms;
	}
}

$res = null;
if (count($_FILES) > 0) {
	$update = new Update();
	$res = $update->handleUpload();
}
?>
<!doctype html>
<html>
<body>
<?php if (isset($res)) { ?>
<form method="post" action="update"
<?php } else { ?>
<form method="post" action="update" enctype="multipart/form-data">
	<input type="file" name="file" id="file">
	<button>Submit</button>
</form>
<?php } ?>
</body>
</html>