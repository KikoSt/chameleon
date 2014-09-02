<?php
require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

$advertiserId = 122;
$companyId    = 170;
$userId       = 14;
$templateId   = 96;
$auditUserId  = 1;

$productCategoryIds = getCategories($companyId, $advertiserId);

foreach($productCategoryIds AS $categoryId)
{
    passthru('php ./generate.php ' . $companyId . ' ' . $advertiserId . ' ' . $categoryId . ' ' . $auditUserId);
    // passthru('php ./cacheimages.php ' . $companyId . ' ' . $advertiserId . ' ' . $categoryId . ' ' . $auditUserId);
}


exit(0);

// Some tests
$connector = new APIConnector();

$connector->setAdvertiserId($advertiserId);
$connector->setCompanyId($companyId);
$connector->setAuditUserId($auditUserId);

$templates = $connector->getTemplates();

// $dummyGroupId = 1;
// $count = 0;
//
// foreach($templates AS $template)
// {
//     echo $template->getDimX() . ' / ' . $template->getDimY(). "\n";
//     echo $template->getGroupId() . "\n";
//     echo $template->getName() . "\n";
//     echo 'Advertiser: ' . $template->getAdvertiserId() . "\n";
//     echo '-------------------' . "\n";
//     $template->setGroupId(null);
//     $connector->sendBannerTemplate($template);
// }

exit(0);

function getCategories($companyId, $advertiserId)
{
    $categoryIdList = array();
    $connector = new APIConnector();

    $connector->setAdvertiserId($advertiserId);
    $connector->setCompanyId($companyId);

    $categories = $connector->getCategories();
    foreach($categories AS $category)
    {
        $categoryIdList[] = $category->id;
    }
    return $categoryIdList;
}

function getUserStatusValues($companyId, $advertiserId, $auditUserId)
{
    $connector = new APIConnector();

    $connector->setAdvertiserId($advertiserId);
    $connector->setCompanyId($companyId);
    $connector->setAuditUserId($auditUserId);

    $userStatusValues = $connector->getUserStatusValues();

    return $userStatusValues;
}


function sendCreatives($companyId, $advertiserId, $categoryId, $auditUserId)
{
    $connector = new APIConnector();

    $connector->setAdvertiserId($advertiserId);
    $connector->setCompanyId($companyId);
    $connector->setAuditUserId($auditUserId);

    // path is <companyId>/<advertiserId>/<categoryId>
    $basePath = OUTPUT_DIR . $companyId . '/' . $advertiserId . '/' . $categoryId . '/';

    foreach(glob($basePath . "*.swf") AS $cur)
    {
        $curBanner = new CreativeModel();
        $swfPath = $cur;
        $gifPath = str_replace('.swf', '.gif', $cur);

        $filename = substr(basename($swfPath), 0, -4);

        list($templateId, $productName, $productId, $size) = explode('_', $filename);
        unset($templateId);
        unset($productName);

        list($height, $width) = explode('x', $size);

        $curBanner->setSwfPath($swfPath);
        $curBanner->setGifPath($gifPath);
        $curBanner->addProductId($productId);
        $curBanner->setWidth($width);
        $curBanner->setHeight($height);

        $connector->sendCreative($curBanner, $categoryId);
    }
}










function getTemplates($companyId, $advertiserId)
{
    $connector = new APIConnector();
    $connector->setAdvertiserId($advertiserId);
    $connector->setCompanyId($companyId);

    $templates = $connector->getTemplates();

    $templateList = array();

    foreach($templates AS $template)
    {
        $templateList[] = $template->getSvgContent();
    }

    return $templateList;
}


function getTemplatesToFile($container)
{
    $connector = new APIConnector();
    $connector->setAdvertiserId($container->getAdvertiserId());
    $connector->setCompanyId($container->getCompanyId());

    $templates = $connector->getTemplates();
    foreach($templates AS $template)
    {
        $filename = 'rtest_' . $template->getBannerTemplateId() . '.svg';

        // write the temporary file
        $fh = fopen(SVG_DIR . $filename, 'w');
        fwrite($fh, $template->getSvgContent());
        fclose($fh);
    }

}


