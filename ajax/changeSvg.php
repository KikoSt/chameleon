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
$connector->setBannerTemplateId($_REQUEST['templateId']);

$template = $connector->getTemplateById();

$baseFilename = 'rtest_' . $connector->getBannerTemplateId();
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

$svgContent = $container->createSvg();

$container->setTarget('GIF');
$container->render();

// write the temporary file
$svgHandler->setSvgContent($svgContent);
$svgHandler->save();


if('save' === $_REQUEST['action'])
{
    //update template in the data base
    $bannerTemplateModel = new BannerTemplateModel();
    $bannerTemplateModel->setSvgContent($svgContent);
    $bannerTemplateModel->setBannerTemplateId($_REQUEST['id']);
    $bannerTemplateModel->setAuditUserId(14); //todo for development, use the given id in the future
    $bannerTemplateModel->setAdvertiserId($container->getAdvertiserId());
    $bannerTemplateModel->setDescription('testing');

    $response = $connector->sendBannerTemplate($bannerTemplateModel);

    var_dump($response);
}







