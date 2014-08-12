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

var_dump($_POST);
var_dump($_FILES);

$container = new GfxContainer();
$connector = new APIConnector();
$svgHandler = new SvgHandler();

$container->setCompanyId($_REQUEST['companyId']);
$container->setAdvertiserId($_REQUEST['advertiserId']);
$connector->setBannerTemplateId($_REQUEST['templateId']);

if(!empty($_FILES))
{
    foreach($_FILES as $singleFile)
    {
        $filename = ASSET_DIR . $singleFile['name'];
        move_uploaded_file($singleFile['tmp_name'], $filename);
    }
}

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
if(null !== $_FILES)
{
    //iterate all svg elements
    foreach($container->getElements() as $element)
    {
        foreach($_FILES as $key => $singleFile)
        {
            if($key === $element->getId())
            {
                $element->setImageUrl("assets/" . $singleFile['name']);
            }
        }
    }
}
else
{
    $container->changeElementValue($_POST);
}

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







