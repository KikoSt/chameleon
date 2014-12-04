<?php
/**
 *
 */
require_once('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');

$container = new GfxContainer();
$connector = new APIConnector();

$companyId      = getRequestVar('companyId');
$advertiserId   = getRequestVar('advertiserId');
$templateId     = getRequestVar('templateId');
$numPreviewPics = getRequestVar('numPreviewPics');

$container->setCompanyId($companyId);
$container->setAdvertiserId($advertiserId);

$connector->setCompanyId($companyId);
$connector->setAdvertiserId($advertiserId);

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
