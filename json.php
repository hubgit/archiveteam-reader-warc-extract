<?php

if (!isset($argv[1])) {
	exit(sprintf("Usage: %s needle\n", $argv[0]));
}

$needle = $argv[1];

$sanitised_needle = preg_replace('/[^a-z0-9]/i', '_', $needle);
$dir = __DIR__ . '/data/json/' . $sanitised_needle;

if (!file_exists($dir)) {
	mkdir($dir, 0777, true);
}

$i = 1;

$files = glob(__DIR__ . '/data/warc/' . $sanitised_needle . '/*.warc');

foreach ($files as $file) {
	print "Parsing $file\n";

	$warc = file_get_contents($file);
	list($head, $http, $body) = explode("\n\r", $warc, 3);

	$lines = explode("\n", $head);
	$headers = array();
	foreach ($lines as $line) {
		$parts = explode(': ', $line, 2);

		if (isset($parts[1])) {
			list($key, $value) = $parts;
			$headers[mb_strtolower($key)] = mb_strtolower($value);
		}
	}

	print_r($headers);
	$url = $headers['warc-target-uri'];

	//$body = preg_replace('/^[^\{]+/', '', $body);
	//$body = preg_replace('/[^\{]+$/', '', $body);

	print $body;
	$data = json_decode($body, true);

	print_r($data);
}