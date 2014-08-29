<?php

require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

$companyId    = (int) $argv[1];
$advertiserId = (int) $argv[2];
$categoryId   = (int) $argv[3];
$auditUserId  = (int) $argv[4];

echo $companyId . ' ' . $advertiserId . ' ' . $categoryId . "\n";

$connector = new APIConnector();
$container = new GfxContainer();

$connector->setAdvertiserId($advertiserId);
$connector->setCompanyId($companyId);
$connector->setAuditUserId($auditUserId);

// fetch all templates for given advertiser
$templates = $connector->getTemplates();

$container->setAdvertiserId($advertiserId);
$container->setCompanyId($companyId);

$container->setCategoryId($categoryId);
$products    = $connector->getProductsByCategory($categoryId);
$productList = $products;

$count = 0;
foreach($templates AS $template)
{
    $container = new GfxContainer();
    $container->setAdvertiserId($advertiserId);
    $container->setCompanyId($companyId);
    $container->setCategoryId($categoryId);
    $container->setSource($template->getSvgContent());
    $container->setId($template->getBannerTemplateId());
    $container->parse();

    foreach($productList AS $product)
    {
        $container->setProductData($product);
        $container->setTarget('SWF');
        $container->render();
        $container->setTarget('GIF');
        $container->render();
        // $container->cleanup();
        echo ++$count . "\n\n";
    }
}

