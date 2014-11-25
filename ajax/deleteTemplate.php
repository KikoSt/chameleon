<?php
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');
require_once('../config/pathconfig.inc.php');
require_once('../libraries/classes/APIConnector.class.php');
require_once('../libraries/classes/BannerTemplateModel.class.php');

$advertiserId = (int)getRequestVar('advertiserId');
$templateId   = (int)getRequestVar('templateId');

//get template via REST API
$connector = new APIConnector();

//delete template
$success = $connector->deleteBannerTemplate((int)$_POST['templateId']);

echo json_encode($success);
