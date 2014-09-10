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
$svgHandler = new SvgFileHandler();

// for now ...
$auditUserId = 14;

$companyId    = getRequestVar('companyId');
$advertiserId = getRequestVar('advertiserId');
$templateId   = getRequestVar('templateId');

$container->setCompanyId($companyId);
$container->setAdvertiserId($advertiserId);

$basePath = (string) $companyId . '/' . (string) $advertiserId . '/0';

if(array_key_exists('action', $_REQUEST))
{
    $action = $_REQUEST['action'];
}
else
{
    return false;
}

if(!empty($_FILES))
{
    foreach($_FILES as $singleFile)
    {
        $filename = ASSET_DIR . '/' . $singleFile['name'];
        move_uploaded_file($singleFile['tmp_name'], $filename);
    }
}

//set file name
$baseFilename = 'rtest_' . $templateId;
$filename = $baseFilename . '.svg';
$container->setOutputName($baseFilename);

//parse the svg
$container->setSource($filename);
$container->setId($templateId);
$container->parse();

// TODO
// FOR NOW, it is of huge importance that this next portion is located before
// the files change section since the changeElementValue method will update
// the imgSources with the old values, being changed (corrected) again below

if($action !==  'upload')
{
    $container->changeElementValue($_POST);
}

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
                $element->setImageUrl('/assets/' . $singleFile['name']);
            }
        }
    }
}

$svgContent = $container->createSvg();
$container->setTarget('GIF');
$container->render();

if($action === 'clone' || $action === 'save')
{
    $connector = new APIConnector();
    $connector->setCompanyId(getRequestVar('companyId'));
    $connector->setAdvertiserId(getRequestVar('advertiserId'));

    //update template in the data base
    $bannerTemplateModel = new BannerTemplateModel();
    $bannerTemplateModel->setSvgContent($svgContent);
    $bannerTemplateModel->setGroupId(0);
    $bannerTemplateModel->setDimX($container->getCanvasHeight());
    $bannerTemplateModel->setDimY($container->getCanvasWidth());
    $bannerTemplateModel->setBannerTemplateId($templateId);
    $bannerTemplateModel->setAuditUserId($auditUserId);
    $bannerTemplateModel->setAdvertiserId($advertiserId);
    $bannerTemplateModel->setDescription('testing');
    $bannerTemplateModel->setName('mumblebee testing');

    if('save' === $action)
    {
        $response = $connector->sendBannerTemplate($bannerTemplateModel);
    }
    else if('clone' === $action)
    {
        $response = $connector->sendBannerTemplate($bannerTemplateModel);
    }
}

$response = array();

// $imgsrc = $container->getOutputDir() . '/' . $container->getOutputName() . '.gif';
// TODO: improve this path handling, too
$imgsrc = 'output/' . $basePath . '/' . $container->getOutputName() . '.gif';
$response['imgsrc'] = $imgsrc;

echo json_encode($response);

