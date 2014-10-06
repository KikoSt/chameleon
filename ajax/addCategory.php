<?php
session_start();
include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');

var_dump($_REQUEST);

if(!empty($_REQUEST))
{
    foreach($_REQUEST as $id => $value)
    {
        var_dump($id);
        var_dump($value);

        if($id !== 'action')
        {
            $_SESSION['category'][$id] = $value;
        }
    }
}
var_dump($_SESSION['category']);





