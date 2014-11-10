<?php
/**
 * To find out, how gif animations work
 */

require_once('Bootstrap.php');
include('config/pathconfig.inc.php');
include('config/fontconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

$connector = new APIConnector();
$container = new GfxContainer();

$connector->setAdvertiserId(122);
$connector->setCompanyId(170);

$template = $connector->getTemplateById(223);

$container->setAdvertiserId(122);
$container->setCompanyId(170);
$container->setCategoryId(0);

$baseFilename = 'gifAnimationProto';
$filename = $baseFilename . '.svg';
$container->setOutputName($baseFilename);

$container->setSource($template->getSvgContent());
$container->setId($template->getBannerTemplateId());

try
{
    $container->parse();
}
catch(Exception $e)
{
    continue;
}

$container->saveSvg();

$container->setTarget('GIF');

$width = $container->getCanvasWidth();
$height = $container->getCanvasHeight();

$elements = $container->getElements();

$container->render();

//canvas
$file = BASE_DIR . "/output/" . $container->getOutputDir() . '/'.$baseFilename.'.gif';
$text = BASE_DIR . "/assets/gifProto/ani.gif";

echo ('<image src="'.$file.'"></image>');
