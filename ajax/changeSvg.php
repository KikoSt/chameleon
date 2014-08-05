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
$svgHandler = new SvgHandler();

$container->setCompanyId($_REQUEST['companyId']);
$container->setAdvertiserId($_REQUEST['advertiserId']);
$connector->setBannerTemplateId($_REQUEST['id']);

$template = $connector->getTemplateById();

$baseFilename = 'rtest_' . $template->getIdBannerTemplate();
$filename = $baseFilename . '.svg';
$container->setOutputName($baseFilename);

// write the temporary file
$svgHandler->setFilename($filename);
$svgHandler->setSvgContent($template->getSvgContent());
$svgHandler->save();

$container->setSource($filename);
$container->parse();

//create a new svg with the given request parameters
$container->changeElementValue($_POST);

$newSvgContent = $container->createSvg();

$selectedButton = 'preview';

if($selectedButton === 'preview')
{
    $container->setTarget('GIF');
    $container->render();

    // write the temporary file
    $svgHandler->setSvgContent($newSvgContent);
    $svgHandler->save();

    var_dump($connector->sendBannerTemplate($newSvgContent));
}







