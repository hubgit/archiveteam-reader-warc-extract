<?php

$dir = __DIR__ . '/data/cdx';

if (!file_exists($dir)) {
	mkdir($dir, 0777, true);
}

$files = glob(__DIR__ . '/data/pages/*.html');

foreach ($files as $file) {
	$doc = new DOMDocument;
	@$doc->loadHTMLFile($file);

	$xpath = new DOMXPath($doc);
	$nodes = $xpath->query('//table[starts-with(@class, "fileFormats ")]//a[starts-with(@href, "/download/")]');

	foreach ($nodes as $node) {
		$href = $node->getAttribute('href');

		// only include warc cdx files
		if (!preg_match('/\.warc\.os\.cdx\.gz$/', $href)) {
			continue;
		}

		$url = 'http://archive.org' . $href;
		$file = $dir . '/' . base64_encode($url) . '.cdx.gz';

		if (!file_exists($file)) {
			print "$url\n";
			copy($url, $file);
		}
	}
}