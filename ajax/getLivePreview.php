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

// $auditUserId    = getRequestVar('auditUserId');;
// $companyId      = getRequestVar('companyId');
// $advertiserId   = getRequestVar('advertiserId');
// $templateId     = getRequestVar('templateId');

$auditUserId    = 333;
$companyId      = 170;
$advertiserId   = 120;
$templateId     = 96;

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

$template = $connector->getTemplateById($templateId);



$categoryIds = array(167463, 167187, 167715, 167811);
$numSamples = 5;

$products = $connector->getProductDataSamples($categoryIds, $numSamples);

$argv = array(null, $companyId, $advertiserId, null, $auditUserId);

$generator = new CMEOGenerator($argv);
$generator->setTemplates(array($templateId));
$generator->setCategories($categoryIds);

$count = 0;

foreach($products AS $product)
{
    echo "\n" . ++$count . "\n";
    $categoryId = $product->getCategoryId();
    $generator->prepareLogfile($categoryId);
    $generator->getContainer()->setCategoryId($categoryId);
    $sourcePath = (string) $companyId . '/' . (string) $advertiserId . '/' . $categoryId;

    $generator->getContainer()->setSource($template->getSvgContent());
    $generator->getContainer()->setId($template->getBannerTemplateId());

    // $generator->getContainer()->setOutputDir('0');
    // $generator->getContainer()->setOutputName(getPreviewFileName($template));

    try
    {
        $generator->getContainer()->parse();
    }
    catch(Exception $e)
    {
        $generator->logMessage('An error occured: ' . $e->getMessage() . "\n");
        continue;
    }

    // move file ...
    $sourceName = '../output/' . $sourcePath . '/' . $generator->getContainer()->getOutputFilename() . '.gif';
    $targetName = '../output/' . $targetPath . '/' . $generator->getContainer()->getOutputFilename() . '.gif';
    rename($sourceName, $targetName);
    $imgsrc = 'output/' . $basePath . '/' . $generator->getContainer()->getOutputFilename();
    echo $imgsrc . "\n";

    $generator->render($product);
}
?>
