<?php

if (!isset($argv[1])) {
	exit(sprintf("Usage: %s needle\n", $argv[0]));
}

$needle = $argv[1];

$sanitised_needle = preg_replace('/[^a-z0-9]/i', '_', $needle);
$dir = __DIR__ . '/data/warc/' . $sanitised_needle;

if (!file_exists($dir)) {
	mkdir($dir, 0777, true);
}

$i = 1;

$files = glob(__DIR__ . '/data/cdx/*.cdx.gz');

foreach ($files as $file) {
	print "Parsing $file\n";

	$input = gzopen($file, 'r');

	while (($line = fgets($input)) !== false) {
		$data = explode(' ', $line);
		$feed = $data[2];

		if (strpos($feed, $needle) !== false) {
			$code = $data[4];
			printf("\t%d feed: %s\n", $code, $feed);

			if ($code != 200) {
				continue;
			}

			list($size, $offset, $filename) = array_slice($data, -3);

			$curl = curl_init('http://archive.org/download/' . $filename);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
			//curl_setopt($curl, CURLOPT_VERBOSE, true);

			$headers = array(
				sprintf('Range: bytes=%d-%d', $offset, $offset + $size)
			);

			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($curl);
			$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if ($code !== 206) {
				print "\t\tBad Response: $code\n";
				continue;
			}

			$output_file = sprintf('%s/%d.warc', $dir, $i++);
			print "\t\t=> $output_file\n";

			file_put_contents($output_file, gzdecode($result));
		}
	}

	if (!feof($input)) {
        print "Error: unexpected fgets() fail\n";
    }

	gzclose($input);
}