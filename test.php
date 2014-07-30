<?php
require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

for($i=1; $i<6;$i++)
{
    $myContainer = new GfxContainer();
    $myContainer->setSource('svg/ttest_' . $i . '.svg');
    $myContainer->setOutputName('output_' . $i);
    $myContainer->parse();
    $myContainer->setTarget('SWF');
    $myContainer->render();
    $myContainer->setTarget('GIF');
    $myContainer->render();
}

$myContainer = new GfxContainer();
$myContainer->setSource('svg/ttest_crit.svg');
$myContainer->setOutputName('output_crit');
$myContainer->parse();
$myContainer->setTarget('SWF');
$myContainer->render();
$myContainer->setTarget('GIF');
$myContainer->render();

// function __autoload($className) {
//     if(file_exists('libraries/classes/' . $className . '.class.php')) {
//         require_once('libraries/classes/' . $className . '.class.php');
//     } else if(file_exists('libraries/interfaces/' . $className . '.interface.php')) {
//         require_once('libraries/interfaces/' . $className . '.interface.php');
//     }
// }
