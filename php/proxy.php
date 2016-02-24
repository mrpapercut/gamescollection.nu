<?php

function fetch($url, $post = false, $params = array(), $cookie = null) {
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYPEER => false
	));

	if ($post === true) {
		curl_setopt_array($curl, array(
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $params
		));
	}

	if ($cookie !== null) {
		curl_setopt($curl, CURLOPT_COOKIESESSION, true);
		curl_setopt($curl, CURLOPT_COOKIE, $cookie);
	}

	$res = curl_exec($curl);

	if ($res === false) {
		$res = curl_error($curl);
	}

	curl_close($curl);

	return $res;
}