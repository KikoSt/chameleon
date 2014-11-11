<?php
// error_reporting(E_ALL);
require_once('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

// get ini settings
$iniSettings = parse_ini_file('../generate.ini');
if(!$iniSettings)
{
    echo basename(__FILE__) . ": Ini file not found, exiting\n";
    exit(1);
}

$advertiserId = 122;
$companyId    = 170;
$userId       = 14;
$templateId   = 96;
$auditUserId  = 1;

// get all categories
try
{
    $productCategoryIds = getCategories($companyId, $advertiserId);
}
catch(Exception $e)
{
    echo $e->getMessage();
    exit(1);
}

// for testing, take a random slice with 5 entries and generate only those ...
$sliceStart = rand(0, count($productCategoryIds));
$productCategoryIds = array_slice($productCategoryIds, $sliceStart, 5);

foreach($productCategoryIds AS $categoryId)
{
    try
    {
        $result = passthru('php ./generate.php ' . $companyId . ' ' . $advertiserId . ' ' . $categoryId . ' ' . $auditUserId);
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
    // passthru('php ./cacheimages.php ' . $companyId . ' ' . $advertiserId . ' ' . $categoryId . ' ' . $auditUserId);
}


exit(1);

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


