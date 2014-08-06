<?php
    require_once('Bootstrap.php');

    if(!defined('__ROOT__'))
    {
        define('__ROOT__', './');
    }
    require_once(__ROOT__ . 'libraries/functions.inc.php');

    include('config/pathconfig.inc.php');

    $advertiserId = 122;
    $companyId = 4;

    $myIndex = new Index();

    session_start();

    $params = array_keys($_REQUEST);
    $modules = array('overview', 'editor');

    if(!in_array('page', $params || !in_array($_REQUEST['page'], $modules)))
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
    require_once('views/footer.phtml');
?>


