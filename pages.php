<?php

$dir = __DIR__ . '/data/pages';

if (!file_exists($dir)) {
	mkdir($dir, 0777, true);
}

$files = glob(__DIR__ . '/data/indexes/*.html');

foreach ($files as $file) {
	$doc = new DOMDocument;
	@$doc->loadHTMLFile($file);

	$xpath = new DOMXPath($doc);
	$nodes = $xpath->query('//table[@class="resultsTable"]//a[@class="titleLink"]');

	foreach ($nodes as $node) {
		$href = $node->getAttribute('href');

		// only include files that start with the appropriate prefix
		if (strpos($href, '/details/archiveteam_greader_') !== 0) {
			continue;
		}

		$url = 'http://archive.org' . $href;
		$file = $dir . '/' . base64_encode($url) . '.html';

		if (!file_exists($file)) {
			print "$url\n";
			copy($url, $file);
		}
	}
}