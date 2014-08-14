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

$container->setCompanyId(getRequestVar('companyId'));
$container->setAdvertiserId(getRequestVar('advertiserId'));

if(!empty($_FILES))
{
    foreach($_FILES as $singleFile)
    {
        $filename = ASSET_DIR . $singleFile['name'];
        move_uploaded_file($singleFile['tmp_name'], $filename);
    }
}

$template = $connector->getTemplateById($bannerTemplateId);

$connector->setCompanyId(getRequestVar('companyId'));
$connector->setAdvertiserId(getRequestVar('advertiserId'));

//set file name
$baseFilename = 'rtest_' . $bannerTemplateId;
$filename = $baseFilename . '.svg';
$container->setOutputName($baseFilename);
$svgHandler->setFilename($filename);

//parse the svg
$container->setSource($filename);
$container->parse();

//create a new svg with the given request parameters
if(null !== $_FILES && count($_FILES) > 0)
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

echo $container->getOutputDir();
echo $container->getOutputName();

// write the temporary file
$svgHandler->setSvgContent($svgContent);
$svgHandler->save();

echo $svgHandler->getFilename();

if(array_key_exists('action', $_REQUEST) && 'save' === $_REQUEST['action'])
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
}







