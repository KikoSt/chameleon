<?php

require_once('config/config.inc.php');

$advertiserId = 122;
$companyId = 170;

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

$redirect = $myIndex->getRedirect($page);

$redirect->setAdvertiserId($advertiserId);
$redirect->setCompanyId($companyId);

// create page
require_once('views/header.phtml');
$redirect->create();
$redirect->display();
//require_once('views/footer.phtml');
