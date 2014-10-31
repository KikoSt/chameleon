<?php
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');
include('../config/pathconfig.inc.php');
require('../libraries/classes/APIConnector.class.php');
require('../libraries/classes/BannerTemplateModel.class.php');

$success = true;

if(!isset($_POST['templateId']))
{
    $success = false;
}

if(!isset($_POST['advertiserId']))
{
    $success = false;
}

if($success)
{
    // get template via REST API
    $connector = new APIConnector();
    $template = $connector->getTemplateById($_POST['templateId']);
    $template->setAdvertiserId((int)$_POST['advertiserId']);

    //store template
    $result = $connector->cloneBannerTemplate($template);

    $clone = json_decode($result);

    echo $result;
}
return $success;




