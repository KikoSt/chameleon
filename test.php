<?php
require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

$advertiserId = 122;
$companyId    = 170;

$connector = new APIConnector();
$container = new GfxContainer();

$connector->setAdvertiserId($advertiserId);
$connector->setCompanyId($companyId);

$container->setAdvertiserId($advertiserId);
$container->setCompanyId($companyId);
$container->setId(108);

$template = $connector->getTemplateById(96);
var_dump($template);
//
die();

// foreach($templates AS $template)
// {
    $svg = simplexml_load_string($template->getSvgContent());
    $nodes = $svg->children();
    $nodes = $nodes->children();

    foreach($nodes AS $node)
    {
        $attributes = $node->attributes();
        echo $attributes->id . "\n";
        if($attributes->id == 'background')
        {
            echo 'setting width and height to ' . $attributes->width . '/' . $attributes->height . "\n";
            // $width  = $attributes->width;
            // $height = $attributes->height;
            $width = 750;
            $height = 300;
            $template->setDimX($width);
            $template->setDimY($height);
        }
    }

    $width = 750;
    $height = 300;
    $container->setSource($template->getSvgContent());
    $container->setCanvasWidth($width);
    $container->setCanvasHeight($height);
    $template->setSvgContent($container->createSvg());
    var_dump($container);
    $connector->sendBannerTemplate($template);
// }


die();

foreach($templates AS $template)
{
    $svg = simplexml_load_string($template->getSvgContent());
    $nodes = $svg->children();
    $nodes = $nodes->children();
    foreach($nodes AS $node)
    {
        $attributes = $node->attributes();
        echo $attributes->id . "\n";
        if($attributes->id == 'background')
        {
            echo 'setting width and height to ' . $attributes->width . '/' . $attributes->height . "\n";
            $template->setDimX($attributes->width);
            $template->setDimY($attributes->height);
        }
    }
    $connector->sendBannerTemplate($template);
}

die();


$ids = array(96, 99, 102, 105, 108);

foreach($ids AS $id)
{

    $filename = 'rtest_' . $id . '.svg';

    $container->setSource($filename);
    $container->parse();

    $template = new BannerTemplateModel();
    $template->setSvgContent($container->getSvg());
    $template->setBannerTemplateId($id);
    $template->setAdvertiserId($advertiserId);
    $template->setDimX(120);
    $template->setDimY(600);
    $template->setDescription('Fixed');
    $template->setAuditUserId(1);
    $template->setName('last');
    $connector->sendBannerTemplate($template);
    var_dump($template);
}

die();

$container->setId(108);

$filename = 'rtest_108.svg';

$container->setSource($filename);
$container->setOutputName('output_108');
$container->parse();



$template = new BannerTemplateModel();
$template->setSvgContent($container->getSvg());
$connector->sendBannerTemplate($template);




die();















// hardcoded by now. For now.
$productCategories = array(7, 10, 11881, 11887, 11890, 11893, 11899, 11902, 11908, 11911, 11917, 11923, 11929, 11932, 11935, 11941, 11944, 11947, 11950, 11956, 11959, 11962, 11968, 11971, 11974, 11977, 11980, 11986, 11989, 11992, 11995, 11998, 12001, 12004, 12007, 12013, 12016, 12019, 12022, 12025, 12028, 12031, 12040, 12043, 12049, 12052, 12055, 12058, 12061, 12067, 12073, 12079, 12082, 12085, 12088, 12091, 12094, 12097, 12100, 12103, 12106, 12112, 12115, 12118, 12124, 12127, 12130, 12133, 12136, 12139, 12142, 12145, 12148, 12151, 12157, 12160, 12163, 12166, 12178, 12181, 12184, 12187, 12190, 12193, 12196, 12199, 12202, 12208, 12211, 12214, 12217, 12220, 12223, 12226, 12229, 12232, 12235, 12238, 12241, 12244, 12247, 12250, 12253, 12256, 12259, 12262, 12265, 12268, 12271, 12274, 12277, 12280, 12283, 12286, 12288, 12291, 12294, 12297, 12300, 12303);

$advertiserId = 122;
$companyId    = 170;
$userId       = 14;
$templateId   = 96;

foreach($productCategories AS $category)
{
    passthru('php ./generate.php ' . $category);
}
die();



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
            // $container = new GfxContainer();
            // $container->setId($template->getBannerTemplateId());
            // $container->setAdvertiserId($advertiserId);
            // $container->setCompanyId($companyId);
            // $container->setCategoryId($category);

            $container->setProductData($product);
            $now = time();
            $container->setTarget('SWF');
            $container->render();
            $then = time();
            echo 'D: ' . ($then - $now) . "\n";
            $now = time();
            $container->setTarget('GIF');
            $container->render();
            $then = time();
            echo 'D: ' . ($then - $now) . "\n";
            $container->cleanup();
            echo ++$count . "\n\n";
        }
    }
}

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

