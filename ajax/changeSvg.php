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

//$container->setId(getRequestVar('id'));
$container->setCompanyId(getRequestVar('companyId'));
$container->setAdvertiserId(getRequestVar('advertiserId'));

$connector->setBannerTemplateId(getRequestVar('id'));
$connector->setcompanyId(getRequestVar('companyId'));
$connector->setAdvertiserId(getRequestVar('advertiserId'));

//set file name
$baseFilename = 'rtest_' . $_REQUEST['templateId'];
$filename = $baseFilename . '.svg';
$container->setOutputName($baseFilename);

//parse the svg
$container->setSource($filename);
$container->parse();

//add the changes to the container
$container->changeElementValue($_POST);

//create the new svg
$svgContent = $container->createSvg();

// write the svg
$svgHandler->setFilename($filename);
$svgHandler->setSvgContent($svgContent);
$svgHandler->save();

// render the new svg for the editor view
$container->setOutputName($baseFilename);
$container->setTarget('GIF');
$container->render();

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







