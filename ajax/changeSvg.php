<?php
session_start();
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

$auditUserId    = getRequestVar('auditUserId');;
$companyId      = getRequestVar('companyId');
$advertiserId   = getRequestVar('advertiserId');
$templateId     = getRequestVar('templateId');

if(!isset($auditUserId) || empty($auditUserId))
{
    return false;
}

$container->setCompanyId($companyId);
$container->setAdvertiserId($advertiserId);

$connector->setCompanyId($companyId);
$connector->setAdvertiserId($advertiserId);

// TODO: get rid of this, container should handle the path and it's advised
// to the the path from the container!
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

$template = $connector->getTemplateById($templateId);

//parse the svg
$container->setSource($template->getSvgContent());
$container->setId($templateId);
$container->parse();

// TODO
// FOR NOW, it is of huge importance that this is executed before
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

if(!empty($action))
{
    $container->render();
}

if($action === 'clone' || $action === 'save' || $action === 'saveCategory')
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

    //TODO while uploading an image, there's no template name present
    //TODO option 1: set a hidden field with the template name
    //TODO option 2: fetch the templateName using the given template id
    //have to figure it out
    if(isset($_REQUEST['templateName']))
    {
        $bannerTemplateModel->setName($_REQUEST['templateName']);
    }

    if($action === 'clone' )
    {
        $response = $connector->cloneBannerTemplate($bannerTemplateModel);
    }

    if($action === 'save')
    {
        $response = $connector->sendBannerTemplate($bannerTemplateModel);
    }
}

$response = array();

// TODO: improve this path handling, too
$container->setOutputName($templateId . '_' . $container->getCanvasHeight() . 'x' . $container->getCanvasWidth());
$imgsrc = 'output/' . $basePath . '/' . $container->getOutputName() . '.gif';
$response['imgsrc'] = $imgsrc;

echo json_encode($response);

