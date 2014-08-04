<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 29.07.14
 * Time: 07:21
 */

include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');


if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

$container = new GfxContainer();
$connector = new APIConnector();
$svgBuilder = new SvgBuilder();

$connector->setBannerTemplateId($_REQUEST['id']);
$template = $connector->getTemplateById();

$baseFilename = 'rtest_' . $template->getIdBannerTemplate();
$filename = $baseFilename . '.svg';
$container->setOutputName($baseFilename);

// write the temporary file
if(is_dir(SVG_DIR))
{
    $fh = fopen(SVG_DIR . $filename, 'w');
    fwrite($fh, $template->getSvgContent());
    fclose($fh);
}
else
{
    throw new Exception(SVG_DIR . ' not found !');
}

$container->setSource($filename);
$container->parse();

//unlink(SVG_DIR . $filename);

//create a new svg with the given request parameters

$container->changeElementValue($_POST);

$newSvg = str_replace('{ELEMENTS}', $container->getSvg(), $svgBuilder->create($container->getCanvasWidth(), $container->getCanvasHeight()));

var_dump($newSvg);

//$container->setTarget('GIF');
//$container->setOutputDestination($container->createDestinationDir());
//$container->calculateOutputDir();
//$container->render();





