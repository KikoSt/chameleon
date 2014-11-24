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
else
{
    $templateId = (int) $_POST['templateId'];
}

if(!isset($_POST['categoryId']))
{
    $success = false;
}
else
{
    $categoryId = (int)$_POST['categoryId'];
}

if(!isset($_POST['advertiserId']))
{
    $success = false;
}
else
{
    $advertiserId = (int)$_POST['advertiserId'];
}

if($success)
{
    // get template via REST API
    $connector = new APIConnector();
    $template = $connector->getTemplateById($templateId);
    $template->setAdvertiserId($advertiserId);

    // remove subscription
    $subscriptions = $template->getCategorySubscriptions();

    foreach ($subscriptions AS $curSubscription)
    {
        if($curSubscription->idCategory == $categoryId)
        {
            $curSubscription->userStatus = 'DELETED';
        }
    }

//     $template->setCategorySubscriptions($subscriptions);

    // store template
    $result = $connector->sendBannerTemplate($template);
}

return $success;
