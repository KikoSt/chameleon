<?php
require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

// hardcoded by now. For now.
$productCategoryIds = array(7, 10, 11881, 11887, 11890, 11893, 11899, 11902, 11908, 11911, 11917, 11923, 11929, 11932, 11935, 11941, 11944, 11947, 11950, 11956, 11959, 11962, 11968, 11971, 11974, 11977, 11980, 11986, 11989, 11992, 11995, 11998, 12001, 12004, 12007, 12013, 12016, 12019, 12022, 12025, 12028, 12031, 12040, 12043, 12049, 12052, 12055, 12058, 12061, 12067, 12073, 12079, 12082, 12085, 12088, 12091, 12094, 12097, 12100, 12103, 12106, 12112, 12115, 12118, 12124, 12127, 12130, 12133, 12136, 12139, 12142, 12145, 12148, 12151, 12157, 12160, 12163, 12166, 12178, 12181, 12184, 12187, 12190, 12193, 12196, 12199, 12202, 12208, 12211, 12214, 12217, 12220, 12223, 12226, 12229, 12232, 12235, 12238, 12241, 12244, 12247, 12250, 12253, 12256, 12259, 12262, 12265, 12268, 12271, 12274, 12277, 12280, 12283, 12286, 12288, 12291, 12294, 12297, 12300, 12303);

$advertiserId = 122;
$companyId    = 170;
$userId       = 14;
$templateId   = 96;
$auditUserId  = 1;

$productCategoryIds = array(20610);


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


