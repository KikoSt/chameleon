<?php
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');
require_once('../libraries/classes/APIConnector.class.php');
require_once('../libraries/classes/BannerTemplateModel.class.php');

$templateId   = (int)getRequestVar('templateId');
$advertiserId = (int)getRequestVar('advertiserId');
// array! id AND name
$category     = getRequestVar('category');

// get template via REST API
$connector = new APIConnector();
$template = $connector->getTemplateById($_POST['templateId']);

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
$success = ($result = $connector->sendBannerTemplate($template));

return json_encode($success);




