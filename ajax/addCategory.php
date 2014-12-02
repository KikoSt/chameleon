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
$category   = $_REQUEST['categoryId'];

// get template via REST API
$connector = new APIConnector();
$template = $connector->getTemplateById($templateId);

$template->setAdvertiserId($advertiserId);
$subscriptions = $template->getCategorySubscriptions();

foreach($category as $singleCategory)
{
    // add subscription
    $newSubscription = new StdClass();
    $newSubscription->idCategory = (int)$singleCategory['id'];
    $newSubscription->userStatus = 'ACTIVE';
    $newSubscription->categoryName = $singleCategory['name'];

    array_push($subscriptions, $newSubscription);
}

$template->setCategorySubscriptions($subscriptions);

// store template
$success = ($result = $connector->sendBannerTemplate($template));

return json_encode($success);




