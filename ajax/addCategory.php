<?php
session_start();
include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');

if(!empty($_REQUEST))
{
    foreach($_REQUEST as $id => $value)
    {
        if($id !== 'action')
        {
            $_SESSION['category'][$id] = $value;
        }
    }
}





