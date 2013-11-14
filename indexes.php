<?php

$dir = __DIR__ . '/data/indexes';

if (!file_exists($dir)) {
	mkdir($dir, 0777, true);
}

foreach (range(1, 8) as $page) {
	$params = array(
		'query' => 'collection:archiveteam_greader',
		'page' => $page,
	);

	$url = 'http://archive.org/search.php?' . http_build_query($params);
	$file = $dir . '/' . $page . '.html';

	if (!file_exists($file)) {
		print "$url\n";
		copy($url, $file);
	}
}