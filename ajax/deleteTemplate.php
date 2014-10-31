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
    //get template via REST API
    $connector = new APIConnector();

    //delete template
    $result = $connector->deleteBannerTemplate((int)$_POST['templateId']);

    echo $result;
}
return $success;