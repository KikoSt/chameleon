<?php


function getRequestVar($identifier)
{
    if(isJSON($_REQUEST))
    {
        $requestVars = json_decode($_REQUEST);
    }
    else
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


function getImageMap($container, $displayComponentLinks)
{
    $textfieldpadding = 3;
    $imageMap = '<map name="template_selection">';
    $elements = $container->getElements();
    $elements = array_reverse($elements);

    if(!$displayComponentLinks)
    {
        foreach($container->getGroups() AS $curGroup)
        {
            $imageMap .= "\n";
            $imageMap .= '<area shape="rect" coords="';
            $imageMap .= (int) $curGroup->getX() . ',';
            $imageMap .= (int) $curGroup->getY() . ',';
            $imageMap .= (int) $curGroup->getX() + $curGroup->getWidth() . ',';
            $imageMap .= (int) $curGroup->getY() + $curGroup->getHeight();
            $imageMap .= '"';
            $imageMap .= ' href="#"';
            $imageMap .= ' alt="group_' . $curGroup->getId() . '"';
            $imageMap .= ' title="group_' . $curGroup->getId() . '"';
            $imageMap .= ' id="group_' .$curGroup->getId() . '"';
            $imageMap .= ' class="maparea"';
            $imageMap .= ' data-key="' . $curGroup->getId() . '_group"';
            $imageMap .= ' />';
        }
    }

    foreach($elements AS $curElement)
    {
         if($displayComponentLinks || $curElement->getEditGroup() == 0)
         {
            $imageMap .= "\n";
            $imageMap .= '<area shape="rect" coords="';
            if($curElement instanceof GfxText)
            {
                $imageMap .= (int) ($curElement->getX() - $textfieldpadding) . ',';
                $imageMap .= (int) ($curElement->getY() - $curElement->getHeight() - $textfieldpadding) . ',';
            }
            else
            {
                $imageMap .= (int) $curElement->getX() . ',';
                $imageMap .= (int) $curElement->getY() . ',';
            }

            if($curElement instanceof GfxText)
            {
                $imageMap .= (int) ($curElement->getX() + $curElement->getWidth() + $textfieldpadding) . ',';
                $imageMap .= (int) ($curElement->getY() + $textfieldpadding);
            }
            else
            {
                $imageMap .= (int) $curElement->getX() + $curElement->getWidth() . ',';
                $imageMap .= (int) $curElement->getY() + $curElement->getHeight();
            }
            $imageMap .= '"';
            $imageMap .= ' href="#"';
            $imageMap .= ' alt="' . $curElement->getId() . '"';
            $imageMap .= ' title="' . $curElement->getId() . '"';
            $imageMap .= ' id="' .$curElement->getId() . '"';
            $imageMap .= ' class="maparea"';
            $imageMap .= ' data-key="';
            $imageMap .= $curElement->getId();
            if($curElement instanceof GfxText) $imageMap .= '_text';
            if($curElement instanceof GfxImage) $imageMap .= '_image';
            if($curElement instanceof GfxRectangle) $imageMap .= '_rectangle';
            $imageMap .= '"';
            $imageMap .= ' />';
        }
    }
    $imageMap .= "\n";
    $imageMap .= '</map>';
    return $imageMap;
}

function getPrunedAvailableCategories($categories, $templateSubscriptions)
{
    $prunedCategories = array();

    foreach($categories as $category)
    {
        $prunedCategories[$category->id] = $category->name;
    }

    foreach($templateSubscriptions as $subscription)
    {
        if($subscription->userStatus === "ACTIVE")
        {
            unset($prunedCategories[$subscription->idCategory]);
        }
    }
    return $prunedCategories;
}
