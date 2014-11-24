<?php
/**
 *
 */
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
$companyId      = getRequestVar('companyId');
$advertiserId   = getRequestVar('advertiserId');
$templateId     = getRequestVar('templateId');
$numPreviewPics = getRequestVar('numPreviewPics');

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

$template = $connector->getTemplateById($templateId);

$productIds = array();

if(!empty($template->getCategorySubscriptions()))
{
    foreach ($template->getCategorySubscriptions() AS $subscription)
    {
        if ($subscription->userStatus === 'ACTIVE')
        {
            $categoryIds[] = $subscription->idCategory;
        }
    }
    $numSamples = ceil($numPreviewPics / count($categoryIds));

    $products = $connector->getProductDataSamples($categoryIds, $numSamples);

    foreach($products AS $product)
    {
        $productIds[] = $product->getProductId();
    }
}

echo json_encode($productIds);

