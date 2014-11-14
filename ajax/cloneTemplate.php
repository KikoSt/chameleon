<?php
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');
include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

$success = true;

if(!isset($_POST['templateId']))
{
    $success = false;
}

if(!isset($_POST['advertiserId']))
{
    $success = false;
}

if(!isset($_POST['companyId']))
{
    $success = false;
}

if($success)
{
    // get template via REST API
    $connector = new APIConnector();
    $container = new GfxContainer();
    $template = $connector->getTemplateById($_POST['templateId']);
    $template->setAdvertiserId((int)$_POST['advertiserId']);

    //store template
    $result = $connector->cloneBannerTemplate($template);

    //get cloned template
    $clone = json_decode($result);

    $templateNew = $connector->getTemplateById($clone->idBannerTemplate);

    $container->setId($clone->idBannerTemplate);
    $container->setcompanyId($_POST['companyId']);
    $container->setAdvertiserId($_POST['advertiserId']);

    $baseFilename = getPreviewFileName($templateNew);

    // render gif for editor view
    $container->setCategoryId(0); // general, so ZERO here
    $container->setOutputName($baseFilename);
    $container->setSource($templateNew->getSvgContent());
    $container->parse();
    $container->setPreviewMode(true);

    $container->setTarget('GIF');
    $container->render();

    echo $templateNew->getBannerTemplateId();
}
return $success;



