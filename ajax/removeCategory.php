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
    $template = $connector->getTemplateById((int)$_POST['templateId']);
    $template->setAdvertiserId((int)$_POST['advertiserId']);

    // remove subscription
    $subscriptions = $template->getCategorySubscriptions();

    $idSubscriptions = array();

    foreach($_POST['category'] as $category)
    {
        var_dump($category);

        $idSubscriptions[(int)$category['id']] = $category['name'];
    }

    foreach ($subscriptions AS $curSubscription)
    {
        if (array_key_exists($curSubscription->idCategory, $idSubscriptions))
        {
            $curSubscription->userStatus = 'DELETED';
        }
    }

    $template->setCategorySubscriptions($subscriptions);

    // store template
    $result = $connector->sendBannerTemplate($template);
}

return $success;




