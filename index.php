<?php
    require_once('Bootstrap.php');

    if(!defined('__ROOT__'))
    {
        define('__ROOT__', './');
    }
    include('config/pathconfig.inc.php');

    $advertiserId = 122;
    $companyId = 4;

    $myIndex = new Index();

    session_start();

    $params = array_keys($_REQUEST);

    if(!in_array('page', $params))
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
    echo $redirect->create();
    require_once('views/footer.phtml');
?>


