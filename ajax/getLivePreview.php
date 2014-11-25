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

$numPreviewPics = 5;

$companyId    = getRequestVar('companyId');
$advertiserId = getRequestVar('advertiserId');
$templateId   = getRequestVar('templateId');

$container->setCompanyId($companyId);
$container->setAdvertiserId($advertiserId);

$connector->setCompanyId($companyId);
$connector->setAdvertiserId($advertiserId);

$targetPath = (string) $companyId . '/' . (string) $advertiserId . '/preview/' . $templateId;
$dir = '../output/' . $targetPath;

$template = $connector->getTemplateById($templateId);

$categoryIds = array();
$files = array();

$categorySubscriptions = $template->getCategorySubscriptions();

// get all ACTIVE category id's
if(count($categorySubscriptions) > 0)
{
    foreach($categorySubscriptions AS $subscription)
    {
        if($subscription->userStatus === 'ACTIVE')
        {
            $categoryIds[] = $subscription->idCategory;
        }
    }
}

if(empty($categoryIds))
{
    exit(json_encode(array()));
}

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


// check if there are already files
$files             = glob($dir . '/*');
$existingProdIds   = array();
$existingProdNames = array();

foreach($files AS $file)
{
    $prodId = str_replace($dir . '/' . $templateId . '_', '', $file);
    $dummyArr = explode('_', $prodId);
    $existingProdIds[] = $dummyArr[1];
}

$numSamples = ceil(($numPreviewPics-count($files)) / count($categoryIds)) * 10;

$products = $connector->getProductDataSamples($categoryIds, $numSamples);

shuffle($products);

$argv = array(null, $companyId, $advertiserId, null, $auditUserId);

$generator = new CMEOGenerator($argv);
$generator->setTemplates(array($templateId));
$generator->setCategories($categoryIds);

$count = 0;

foreach($products AS $product)
{
    if(in_array($product->getProductId(), $existingProdIds) || in_array($product->getName(), $existingProdNames))
    {
        continue;
    }
    $categoryId = $product->getCategoryId();
    $existingProdNames[] = $product->getName();

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

    if(++$count > $numPreviewPics)
    {
        break;
    }
}

foreach($files AS &$file)
{
    $file = str_replace('../', '', $file);
}
unset($file);

echo json_encode($files);
