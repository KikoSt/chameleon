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
$productCategories = array(7, 10, 11881, 11887, 11890, 11893, 11899, 11902, 11908);
$advertiserId = 122;
$companyId    = 170;
$userId       = 14;
$templateId   = 96;

$connector = new APIConnector();
$container = new GfxContainer();

$connector->setAdvertiserId($advertiserId);
$connector->setCompanyId($companyId);

$productList = array();

// fetch all templates for given advertiser
$templates = $connector->getTemplates($advertiserId);


$container->setAdvertiserId($advertiserId);
$container->setCompanyId($companyId);

foreach($productCategories AS $category)
{
    foreach($templates AS $template)
    {
        $container->setSource($template->getSvgContent());
        $container->setId($template->getBannerTemplateId());
        $container->setAdvertiserId($advertiserId);
        $container->setCompanyId($companyId);
        $container->parse();

        $products  = $connector->getProductsByCategory($category);
        $container->setCategoryId($category);
        $productList = array_merge($productList, $products);

        $count = 0;
        foreach($productList AS $product)
        {
            unset($container);

            $container = new GfxContainer();
            $container->setSource($template->getSvgContent());
            $container->setId($template->getBannerTemplateId());
            $container->setAdvertiserId($advertiserId);
            $container->setCompanyId($companyId);
            $container->setCategoryId($category);
            $container->parse();

            $container->setProductData($product);
            $container->setTarget('SWF');
            $container->render();
            $container->setTarget('GIF');
            $container->render();
            echo ++$count . "\n\n";
        }
    }
}

//$templates = getTemplates($companyId, $advertiserId);
//




die();





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

    return($templateList);
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

