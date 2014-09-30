<?php

include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');

session_start();

if(!empty($_REQUEST))
{
    unset($_SESSION['category']);
    $_SESSION['category'] = $_REQUEST;
}




