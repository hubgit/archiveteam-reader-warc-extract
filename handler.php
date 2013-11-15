<?php

require __DIR__ . '/chunked.php';

$feed = isset($_GET['feed']) ? $_GET['feed'] : null;

if ($feed) {
    $params = array(
        'matchType' => 'exact',
        'output' => 'json',
        'fl' => 'length,offset,filename',
        'url' => 'https://www.google.com/reader/api/0/stream/contents/feed/' . $feed,
    );

    $url = 'http://web.archive.org/cdx/search/cdx?' . http_build_query($params);
    $json = file_get_contents($url);
    $results = json_decode($json, true);

    if (empty($results)) {
        header('HTTP/1.0 404 Not Found');
        print 'Feed not found';
        exit();
    }

    list($fields, $result) = $results;
    $data = array_combine($fields, $result);

    $headers = array(
        sprintf('Range: bytes=%d-%d', $data['offset'], $data['offset'] + $data['length'])
    );

    $curl = curl_init('http://archive.org/download/' . $data['filename']);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $warc = gzdecode(curl_exec($curl));
    list($warc_header, $http_header, $body) = explode("\n\r", $warc);

    if (stripos($http_header, 'Transfer-Encoding: chunked') !== false) {
        $body = http_chunked_decode($body);
    }

    header('Content-Type: application/json');
    print $body;
    exit();
}

$prefix = isset($_GET['prefix']) ? $_GET['prefix'] : null;

if ($prefix) {
    $params = array(
        'matchType' => 'prefix',
        'output' => 'json',
        'fl' => 'original,statuscode,length,offset,filename',
        'url' => 'https://www.google.com/reader/api/0/stream/contents/feed/' . rawurlencode($prefix),
    );

    $url = 'http://web.archive.org/cdx/search/cdx?' . http_build_query($params);
    $json = file_get_contents($url);
    $results = json_decode($json, true);
    $fields = array_shift($results);
}
