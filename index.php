<?php
require_once('config/config.inc.php');

$auditUserId  = 666;
$advertiserId = 122;
$companyId    = 170;

$myIndex = new Index();

session_start();

$params = array_keys($_REQUEST);
$modules = array('overview', 'editor');

if(!in_array('page', $params) || !in_array($_REQUEST['page'], $modules))
{
    $page = 'overview';
}
else
{
    $page = $_REQUEST['page'];
}

$pageController = $myIndex->getController($page);

$pageController->setAdvertiserId($advertiserId);
$pageController->setCompanyId($companyId);
$pageController->setAuditUserId($auditUserId);

// create page
require_once('views/header.phtml');
$pageController->create();
$pageController->display();
//require_once('views/footer.phtml');
