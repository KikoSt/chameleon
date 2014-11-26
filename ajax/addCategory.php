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

if(!isset($_POST['category']))
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

    // prepare required variables
    $templateId = $_POST['templateId'];
    $advertiserId = $_POST['advertiserId'];

    $template->setAdvertiserId((int)$advertiserId);
    $subscriptions = $template->getCategorySubscriptions();

    foreach($_POST['category'] as $category)
    {
        // add subscription
        $newSubscription = new StdClass();
        $newSubscription->idCategory = (int)$category['id'];
        $newSubscription->userStatus = 'ACTIVE';
        $newSubscription->categoryName = $category['name'];

        array_push($subscriptions, $newSubscription);
    }

    $template->setCategorySubscriptions($subscriptions);

    // store template
    $result = $connector->sendBannerTemplate($template);
}

return json_encode($success);




