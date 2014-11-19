<?php
include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');

$container = new GfxContainer();
$connector = new APIConnector();

$numPreviewPics = 10;

// $auditUserId    = getRequestVar('auditUserId');;
$companyId      = getRequestVar('companyId');
$advertiserId   = getRequestVar('advertiserId');
$templateId     = getRequestVar('templateId');

$auditUserId    = 1; // system

if(!isset($auditUserId) || empty($auditUserId))
{
    return false;
}

$container->setCompanyId($companyId);
$container->setAdvertiserId($advertiserId);

$connector->setCompanyId($companyId);
$connector->setAdvertiserId($advertiserId);
$connector->setAuditUserId($auditUserId);

$targetPath = (string) $companyId . '/' . (string) $advertiserId . '/preview/' . $templateId;

$dir = '../output/' . $targetPath;

if(!file_exists($dir))
{
    // set the current umask to 0777
    $old = umask(0);
    if(!mkdir($dir, 0777, true))
    {
        throw new Exception('Could not create directory ' . $dir);
    }
    // reset umask
    umask($old);
}
else
{
//    $files = glob($dir . '/*');
//    foreach($files as $file)
//    {
//        if(is_file($file))
//        {
//            unlink($file);
//        }
//    }
}
$template = $connector->getTemplateById($templateId);

foreach($template->getCategorySubscriptions() AS $subscription)
{
    if($subscription->userStatus === 'ACTIVE')
    {
        $categoryIds[] = $subscription->idCategory;
    }
}

$numSamples = ceil($numPreviewPics / count($categoryIds));

$products = $connector->getProductDataSamples($categoryIds, $numSamples);

$argv = array(null, $companyId, $advertiserId, null, $auditUserId);

$generator = new CMEOGenerator($argv);
$generator->setTemplates(array($templateId));
$generator->setCategories($categoryIds);

$count = 0;

$files = array();

foreach($products AS $product)
{
    $categoryId = $product->getCategoryId();
    $generator->prepareLogfile($categoryId);
    $generator->getContainer()->setCategoryId($categoryId);
    $sourcePath = (string) $companyId . '/' . (string) $advertiserId . '/' . $categoryId;

    $generator->getContainer()->setSource($template->getSvgContent());
    $generator->getContainer()->setId($template->getBannerTemplateId());

    try
    {
        $generator->getContainer()->parse();
    }
    catch(Exception $e)
    {
        $generator->logMessage('An error occured: ' . $e->getMessage() . "\n");
        continue;
    }
    $generator->render($product, 'GIF');

    // move file ...
    $sourceName = '../output/' . $sourcePath . '/' . $generator->getContainer()->getOutputFilename() . '.gif';
    $targetName = '../output/' . $targetPath . '/' . $generator->getContainer()->getOutputFilename() . '.gif';
    $fileName = 'output/' . $targetPath . '/' . $generator->getContainer()->getOutputFilename() . '.gif';

    rename($sourceName, $targetName);

    if(file_exists($targetName))
    {
        $files[] = $fileName;
    }
}

echo json_encode($files);

