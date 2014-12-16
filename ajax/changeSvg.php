<?php
session_start();
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 29.07.14
 * Time: 07:21
 */

require_once('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');

$container  = new GfxContainer();
$connector  = new APIConnector();
$svgHandler = new SvgFileHandler();

$auditUserId  = (int)getRequestVar('auditUserId');;
$companyId    = (int)getRequestVar('companyId');
$advertiserId = (int)getRequestVar('advertiserId');
$templateId   = (int)getRequestVar('templateId');
$action       = getRequestVar('action');
if(isset($_REQUEST['mode']))
{
    $mode = $_REQUEST['mode'];
}
else
{
    $mode = 'animated';
}

$container->setCompanyId($companyId);
$container->setAdvertiserId($advertiserId);

$connector->setCompanyId($companyId);
$connector->setAdvertiserId($advertiserId);

// TODO: get rid of this, container should handle the path and it's advised
// to the the path from the container!
$basePath = (string) $companyId . '/' . (string) $advertiserId . '/0';

/**
 * handle file uploads
 */
if(!empty($_FILES))
{
    foreach($_FILES as $singleFile)
    {
        $filename = ASSET_DIR . '/' . $singleFile['name'];
        move_uploaded_file($singleFile['tmp_name'], $filename);
    }
}

if(isset($mode))
{
    if($mode === 'static')
    {
        $container->animatePreviews(false);
    }
    else
    {
        $container->animatePreviews(true);
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
$container->setOutputName(getPreviewFileName($template));
$container->setTarget('SWF');
$container->render();

$container->setTarget('GIF');
$container->render();

if($action === 'save')
{
    $connector = new APIConnector();
    $connector->setCompanyId(getRequestVar('companyId'));
    $connector->setAdvertiserId(getRequestVar('advertiserId'));

    $bannerTemplateModel = new BannerTemplateModel();

    $bannerTemplateModel->setSvgContent($svgContent);
    $bannerTemplateModel->setGroupId(0);
    $bannerTemplateModel->setDimY($container->getCanvasHeight());
    $bannerTemplateModel->setDimX($container->getCanvasWidth());
    $bannerTemplateModel->setBannerTemplateId($templateId);
    $bannerTemplateModel->setAuditUserId($auditUserId);
    $bannerTemplateModel->setAdvertiserId($advertiserId);
    $bannerTemplateModel->setDescription('testing');
    $bannerTemplateModel->setName($_REQUEST['templateName']);

    $response = $connector->sendBannerTemplate($bannerTemplateModel);
}

$response = array();

// TODO: improve this path handling, too
$container->setOutputName(getPreviewFileName($template));
$imgsrc = 'output/' . $basePath . '/' . $container->getOutputName();

$success = chmod('../' . $imgsrc . '.gif', 0777);

$response['action'] = $action;
$response['imgsrc'] = $imgsrc;
$response['gifFilesize'] = filesize('../' . $imgsrc . '.gif');
$response['swfFilesize'] = filesize('../' . $imgsrc . '.swf');

echo json_encode($response);
