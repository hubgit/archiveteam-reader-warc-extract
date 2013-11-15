<?php

// From http://www.php.net/manual/en/function.http-chunked-decode.php#89786

/**
 * dechunk an http 'transfer-encoding: chunked' message
 *
 * @param string $chunk the encoded message
 * @return string the decoded message
 */
function http_chunked_decode($chunk) {
    $pos = 0;
    $len = strlen($chunk);
    $dechunk = null;

    while (($pos < $len) && ($chunkLenHex = substr($chunk, $pos, ($newlineAt = strpos($chunk, "\n" , $pos + 1)) - $pos))) {
        $pos = $newlineAt + 1;
        $chunkLen = hexdec(rtrim($chunkLenHex, "\r\n"));
        $dechunk .= substr($chunk, $pos, $chunkLen);
        $pos = strpos($chunk, "\n", $pos + $chunkLen) + 1;
    }

    return $dechunk;
}
