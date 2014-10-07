<?php


function getRequestVar($identifier)
{
    if(isJSON($_REQUEST))
    {
        $requestVars = json_decode($_REQUEST);
    } else
    {
        $requestVars = $_REQUEST;
    }

    if(isset($requestVars[$identifier]) && null !== $requestVars[$identifier])
    {
        $returnValue = $requestVars[$identifier];
    }
    else
    {
        throw new Exception($identifier . ' not provided');
    }
    return $returnValue;
}

function isJSON($string)
{
    @json_decode($string);
    if(json_last_error() === JSON_ERROR_NONE)
    {
        return false;
    }
    else
    {
        return true;
    }
}

function parseJavaTimestamp($timestamp)
{
    return $timestamp/1000;
}

function getRemoteFileSize($url)
{
    static $regex = '/^Content-Length: *+\K\d++$/im';
    if (!$fp = @fopen($url, 'rb')) {
        return false;
    }
    if (
        isset($http_response_header) &&
        preg_match($regex, implode("\n", $http_response_header), $matches)
    ) {
        return number_format((int)$matches[0] / 1000, 2);
    }

    $fileSize = strlen(stream_get_contents($fp)) / 1000;

    return $fileSize;
}

function getRemoteFileDate($url)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL,$url);
    curl_setopt($c, CURLOPT_HEADER,1);//Include Header In Output
    curl_setopt($c, CURLOPT_NOBODY,1);//Set to HEAD & Exclude body
    curl_setopt($c, CURLOPT_RETURNTRANSFER,1);//No Echo/Print
    curl_setopt($c, CURLOPT_TIMEOUT,5);//5 seconds max, to get the HEAD header.
    curl_setopt($c, CURLOPT_FILETIME, true);
    $cURL_RESULT = curl_exec($c);

    if($cURL_RESULT !== FALSE)
    {
        return date("Y-m-d", curl_getinfo($c, CURLINFO_FILETIME));
    }
}
