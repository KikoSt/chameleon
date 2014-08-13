<?php
require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

// test categories for now are 7 and 10!
$productCategories = array(
31,
37,
40,
52,
61,
64,
70,
82,
85,
88,
91,
94,
97,
100,
103,
106,
121,
133,
136,
142,
145,
151,
154,
157,
160,
163,
166,
169,
175,
178,
181,
184,
190,
196,
199,
202,
205,
211,
214,
217,
223);
$productCategories = array(7, 10);

$advertiserId = 122;
$companyId    = 134;
$companyId    = 17;
$companyId    = 170;
$userId       = 14;

$connector = new APIConnector();
$container = new GfxContainer();

$container->setAdvertiserId($advertiserId);
$container->setCompanyId($companyId);

$connector->setAdvertiserId($advertiserId);
$connector->setCompanyId($companyId);

$productList = array();

// fetch all templates for given advertiser
// $templates = $connector->getTemplates($advertiserId);


$filename = 'rtest_102.svg';

$container->setSource($filename);
//$container->setOutputName('output_102');
$container->setId(102);
$container->parse();


foreach($productCategories AS $category)
{
    $products  = $connector->getProductsByCategory($category);
    $productList = array_merge($productList, $products);
}

echo 'CompanyId: ' . $companyId . ', categoryIds: ' .implode(', ', $productCategories) . "\n";
// var_dump($productList);
// die();

$count = 0;

foreach($productList AS $product)
{
    $container->setProductData($product);
    // $container->setOutputName('output_102_' . $count);
    $container->setTarget('SWF');
    $container->render();
    $container->setTarget('GIF');
    $container->render();
    echo $product . "\n\n";
    $count++;
}


die();

foreach($templates AS $template)
{
    // for now, we stick to the "old" process - reading the svg from a file - in order to prevent more merge
    // conflicts than necessary; changing the process will be very easy and done after thomas hummel's changes
    // have been merged
    $filename = 'rtest_' . $template->getBannerTemplateId() . '.svg';

    // write the temporary file
    $fh = fopen(SVG_DIR . $filename, 'w');
    fwrite($fh, $template->getSvgContent());
    fclose($fh);

    $container->setSource($filename);
    $container->setOutputName('output_' . $template->getBannerTemplateId() . time());
    $container->parse();
    $container->setTarget('SWF');
    $container->render();
    $container->setTarget('GIF');
    $container->render();

    unlink(SVG_DIR . $filename);
}

echo 'Advertiser ' . $advertiserId . ' has ' . $connector->getNumTemplates($advertiserId) . ' templates.' . "\n";

exit(0);

for($i=1; $i<6;$i++)
{
    $myContainer = new GfxContainer();
    $myContainer->setCompanyId(4);
    $myContainer->setAdvertiserId(122);
    $myContainer->setId($i);
    $myContainer->setSource('ttest_' . $i . '.svg');
    // $myContainer->setOutputName('output_' . $i);
    $myContainer->parse();
    $myContainer->setTarget('SWF');
    $myContainer->render();
    $myContainer->setTarget('GIF');
    $myContainer->render();
}

