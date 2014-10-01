<?php
session_start();

include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');

if(!empty($_REQUEST['id']) || $_REQUEST['id'] !== 'undefined')
{
    unset($_SESSION['category'][$_REQUEST['id']]);
}
