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

$basePath = (string) $companyId . '/' . (string) $advertiserId . '/0';

$template = $connector->getTemplateById($templateId);

$categoryIds = array(167187, 167715, 167811);
$numSamples = 5;

$productSamples = $connector->getProductDataSamples($categoryIds, $numSamples);

var_dump($productSamples);

$products = json_decode($productSamples);

echo "\n";

foreach($products AS $sample)
{
    var_dump($sample);
    echo "\n";
}

// var_dump($template);
die();

$products = $connector->getProductsByCategory();

?>
