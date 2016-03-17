<?php

function parseDate($date) {
	preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})/', $date, $matches);

	return mktime(0, 0, 0, $matches[3], $matches[2], $matches[1]) * 1000;
}

function parseGamelist($list) {
	$res = array();

	foreach($list as $game) {
		if (!isset($game->{'is_wishlist_item'})) {
			array_push($res, array(
				'id' => $game->id,
				'name' => $game->name,
				'platformId' => $game->platform_id,
				'image' => array(
					'large' => isset($game->image_url_large) ? $game->image_url_large : null,
					'medium' => isset($game->image_url_medium) ? $game->image_url_medium : null,
					'small' => isset($game->image_url_small) ? $game->image_url_small : null,
					'thumb' => isset($game->image_url_thumb) ? $game->image_url_thumb : null
				),
				'description' => isset($game->description_short) ? $game->description_short : null,
				'release_date' => isset($game->release_date) ? parseDate($game->release_date) : null
			));
		}
	}

	return $res;
}

function parsePlatforms($list) {
	$res = array();

	foreach($list as $platform) {
		array_push($res, array(
			'id' => $platform->id,
			'name' => $platform->name
		));
	}

	return $res;
}

$file = null;
$gamelist = null;
$platforms = null;

if (count($_FILES) > 0) {
	$zip = new ZipArchive;
	if ($zip->open($_FILES['file']['tmp_name']) === true) {
		$file = json_decode($zip->getFromIndex(0));
		if ($file) {
			$gamelist = parseGamelist($file->{'backup'}->{'Game'});
			$platforms = parsePlatforms($file->{'backup'}->{'Platform'});
		}
		$zip->close();
	}
}
?>
<!doctype html>
<html>
<body>
<?php  ?>
<form method="post" action="" enctype="multipart/form-data">
	<input type="file" name="file" id="file">
	<button>Submit</button>
</form>
<?php if ($gamelist) { ?>
	<pre><?php echo json_encode($gamelist); ?></pre>
	<pre><?php echo json_encode($platforms); ?></pre>
<?php } ?>
</body>
</html>