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


function getPreviewFileName($template)
{
    if(!defined('PREVIEW_NAME'))
    {
        $previewFileName = 'preview';
    }
    else
    {
        $datetime    = new Datetime();
        $dateStr     = $datetime->format('Y-m-d');
        $timeStr     = $datetime->format('H:i:s');
        $dateTimeStr = $dateStr . ' ' . $timeStr;

        $placeholders    = array('<advertiserId>', '<templateId>', '<width>', '<height>', '<date>', '<datetime>');
        $replacements    = array($template->getAdvertiserId(), $template->getBannerTemplateId(), $template->getDimX(), $template->getDimY(), $dateStr, $dateTimeStr);
        $previewFileName = str_replace($placeholders, $replacements, PREVIEW_NAME);
    }
    return $previewFileName;
}


function getImageMap($container)
{
    $imageMap = '<map name="template_selection">';
    $elements = $container->getElements();
    $elements = array_reverse($elements);
    foreach($elements AS $curElement)
    {
        $imageMap .= "\n";
        $imageMap .= '<area shape="rect" coords="';
        $imageMap .= (int) $curElement->getX() . ',';
        if($curElement instanceof GfxText)
        {
            $imageMap .= (int) $curElement->getY() - $curElement->getHeight() . ',';
        }
        else
        {
            $imageMap .= (int) $curElement->getY() . ',';
        }

        $imageMap .= (int) $curElement->getX() + $curElement->getWidth() . ',';
        if($curElement instanceof GfxText)
        {
            $imageMap .= (int) $curElement->getY();
        }
        else
        {
            $imageMap .= (int) $curElement->getY() + $curElement->getHeight();
        }
        $imageMap .= '"';
        $imageMap .= ' alt="' . $curElement->getId() . '"';
        $imageMap .= ' title="' . $curElement->getId() . '"';
        $imageMap .= ' id="' .$curElement->getId() . '"';
        $imageMap .= ' class="subnav"';
        $imageMap .= ' />';
    }
    $imageMap .= "\n";
    $imageMap .= '</map>';
    return $imageMap;
}
