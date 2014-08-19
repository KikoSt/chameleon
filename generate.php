<?php

require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

$category = $argv[1];

$advertiserId = 122;
$companyId    = 170;
$userId       = 14;
$templateId   = 96;

$connector = new APIConnector();
$container = new GfxContainer();

$connector->setAdvertiserId($advertiserId);
$connector->setCompanyId($companyId);

// fetch all templates for given advertiser
$templates = $connector->getTemplates($advertiserId);

$container->setAdvertiserId($advertiserId);
$container->setCompanyId($companyId);

$container->setCategoryId($category);
$products  = $connector->getProductsByCategory($category);
$productList = $products;

$count = 0;
foreach($templates AS $template)
{
    $container = new GfxContainer();
    $container->setAdvertiserId($advertiserId);
    $container->setCompanyId($companyId);
    $container->setCategoryId($category);
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
        $container->cleanup();
        echo ++$count . "\n\n";
    }
}

