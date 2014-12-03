<?php
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');
require_once('../libraries/classes/APIConnector.class.php');
require_once('../libraries/classes/BannerTemplateModel.class.php');

$companyId    = (int)getRequestVar('companyId');
$advertiserId = (int)getRequestVar('advertiserId');
$templateId   = (int)getRequestVar('templateId');
$categoryId   = $_REQUEST['categoryId'];

if(is_array($categoryId))
{
    foreach($categoryId as $singleCategory)
    {
        $purgedCategoryId[] = $singleCategory['id'];
    }
}
else
{
    $purgedCategoryId[] = $categoryId;
}


// get template via REST API
$connector = new APIConnector();
$template = $connector->getTemplateById($templateId);
$template->setAdvertiserId($advertiserId);

// remove subscription
$subscriptions = $template->getCategorySubscriptions();

if(count($subscriptions > 0))
{
    foreach ($subscriptions AS $curSubscription)
    {
        if(in_array($curSubscription->idCategory, $purgedCategoryId))
        {
            $curSubscription->userStatus = 'DELETED';
        }
    }
}
// store template
$success = ($result = $connector->sendBannerTemplate($template));

echo json_encode($success);
