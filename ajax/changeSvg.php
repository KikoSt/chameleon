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

require_once(__ROOT__ . 'libraries/functions.inc.php');

$container = new GfxContainer();
$connector = new APIConnector();
$svgHandler = new SvgFileHandler();

$bannerTemplateId = getRequestVar('templateId');

//$container->setId(getRequestVar('id'));
$container->setCompanyId(getRequestVar('companyId'));
$container->setAdvertiserId(getRequestVar('advertiserId'));

// $connector->setBannerTemplateId(getRequestVar('id'));
$connector->setCompanyId(getRequestVar('companyId'));
$connector->setAdvertiserId(getRequestVar('advertiserId'));

//set file name
$baseFilename = 'rtest_' . $bannerTemplateId;
$filename = $baseFilename . '.svg';
$container->setOutputName($baseFilename);

//parse the svg
$container->setSource($filename);
$container->parse();

//create a new svg with the given request parameters
$container->changeElementValue($_POST);

$svgContent = $container->createSvg();

$container->setTarget('GIF');
$container->render();

// write the temporary file
$svgHandler->setFilename($baseFilename);
$svgHandler->setSvgContent($svgContent);
$svgHandler->save();

if('save' === $_REQUEST['action'])
{
    //update template in the data base
    $bannerTemplateModel = new BannerTemplateModel();
    $bannerTemplateModel->setSvgContent($svgContent);
    $bannerTemplateModel->setBannerTemplateId($_REQUEST['templateId']);
    $bannerTemplateModel->setAuditUserId(14); //todo for development, use the given id in the future
    $bannerTemplateModel->setAdvertiserId($container->getAdvertiserId());
    $bannerTemplateModel->setDescription('testing');
    $bannerTemplateModel->setName('bumblebee testing');

    $response = $connector->sendBannerTemplate($bannerTemplateModel);

    var_dump($response);
}







