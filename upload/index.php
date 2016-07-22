<?php

require_once('../php/definitions.inc.php');
require_once('../php/DB.class.php');

class Upload {
	public $file = null;
	public $gamelist = null;
	public $platforms = null;
	public $db;

	private function parseDate($date) {
		preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})/', $date, $matches);

		return mktime(0, 0, 0, $matches[3], $matches[2], $matches[1]) * 1000;
	}

	public function setFile($file) {
		$this->file = $file;
	}

	public function parseGamelist() {
		if (!$this->file) return false;

		$db = new DB();
		$list = $this->file->{'backup'}->{'Game'};
		$games = array();

		foreach($list as $game) {
			if (!isset($game->{'is_wishlist_item'})) {
				if (!$db->id_exists('games', $game->id)) {
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

		foreach ($games as $g) {
			$db->insert('games', $g);
		}

		$this->gamelist = $games;
	}

	public function parsePlatforms() {
		if (!$this->file) return false;

		$list = $this->file->{'backup'}->{'Platform'};
		$res = array();

		foreach($list as $platform) {
			array_push($res, array(
				'id' => $platform->id,
				'name' => $platform->name
			));
		}

		$this->platforms = $res;
	}

	public function platformById($id) {
		if (!$this->platforms) return;

		return $this->platforms[array_search($id, array_column($this->platforms, 'id'))]['name'];
	}
}

$upload = new Upload();

if (isset($_POST['uploadfile']) && count($_FILES) > 0) {
	$zip = new ZipArchive;
	if ($zip->open($_FILES['file']['tmp_name']) === true) {
		$file = json_decode($zip->getFromIndex(0));
		if ($file) {
			$upload->setFile($file);
			$upload->parseGamelist();
			$upload->parsePlatforms();
		}
		$zip->close();
	}
} elseif (isset($_POST['import'])) {
	//$upload->importList();
}

?>
<!doctype html>
<html>
<head>
<style>
th {
	text-align: left;
}
img {
	width: 50px;
	height: 50px;
}
</style>
</head>
<body>
<?php  ?>
<form method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="uploadfile" value="1">
	<input type="file" name="file" id="file">
	<button>Submit</button>
</form>
<?php if ($upload->gamelist) { ?>
	<table>
	<thead><th>Name</th><th>Platform</th><th>Image</th><th>Description</th><th>Release date</th></thead>
	<tbody>
	<?php foreach($upload->gamelist as $g) {
		$images = json_decode($g['images']);
		echo '<tr><td>'.$g['name'].'</td>';
		echo '<td>'.$upload->platformById($g['platformId']).'</td>';
		echo isset($images) ? '<td><img src='.$images['thumb'].'></td>' : null;
		echo '<td>'.$g['description'].'</td>';
		echo '<td>'.$g['release_date'].'</td></tr>'."\n";
	} ?>
	</tbody>
	</table>
	<form method="post" action="" enctype="multipart/form-data">
		<button>Import</button>
	</form>
<?php } ?>
<?php if ($upload->platforms && false) { ?>
	<table>
	<thead><th>ID</th><th>Name</th></thead>
	<tbody>
	<?php foreach($platforms as $platform) {
		echo '<tr><td>'.$platform['id'].'</td><td>'.$platform['name'].'</td></tr>\n';
	} ?>
	</tbody>
	</table>
<?php } ?>
</body>
</html>