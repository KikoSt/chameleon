<?php
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');
//include('../config/pathconfig.inc.php');
require('../libraries/classes/APIConnector.class.php');
require('../libraries/classes/BannerTemplateModel.class.php');

$success = true;

if(!isset($_POST['templateId']))
{
    $success = false;
}

if(!isset($_POST['categoryName']))
{
    $success = false;
}

if(!isset($_POST['categoryId']))
{
    $success = false;
}

if(!isset($_POST['advertiserId']))
{
    $success = false;
}


if($success)
{
    // prepare required variables
    $templateId   = $_POST['templateId'];
    $categoryName = $_POST['categoryName'];
    $categoryId   = $_POST['categoryId'];
    $advertiserId = $_POST['advertiserId'];

    // get template via REST API
    $connector = new APIConnector();
    $template = $connector->getTemplateById($_POST['templateId']);

    var_dump($template);

    // add subscription
    $newSubscription = new StdClass();
    $newSubscription->idCategory = (int) $categoryId;
    $newSubscription->userStatus = 'ACTIVE';
    $newSubscription->categoryName = $categoryName;

    $subscriptions = $template->getCategorySubscriptions();
    array_push($subscriptions, $newSubscription);
    $template->setCategorySubscriptions($subscriptions);
    $template->setAdvertiserId((int) $advertiserId);

    // store template
    $result = $connector->sendBannerTemplate($template);
}

return $success;




