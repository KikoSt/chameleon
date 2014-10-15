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
    // get template via REST API

    $templateId   = (int) $_POST['templateId'];
    $categoryName = $_POST['categoryName'];
    $categoryId   = (int) $_POST['categoryId'];
    $advertiserId = (int) $_POST['advertiserId'];

    $connector = new APIConnector();
    $template = $connector->getTemplateById($_POST['templateId']);

    // remove subscription
    $subscriptions = $template->getCategorySubscriptions();

    foreach($subscriptions AS $curSubscription)
    {
        if($curSubscription->idCategory === $categoryId)
        {
            $curSubscription->userStatus = 'DELETED';
        }
    }

    $template->setCategorySubscriptions($subscriptions);
    $template->setAdvertiserId((int) $advertiserId);

    // store template
    $result = $connector->sendBannerTemplate($template);
}

return $success;




