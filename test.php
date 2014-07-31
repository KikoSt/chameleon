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
    $myContainer->setCompanyId(4);
    $myContainer->setAdvertiserId(122);
    $myContainer->setId($i);
    $myContainer->setSource('ttest_' . $i . '.svg');
    // $myContainer->setOutputName('output_' . $i);
    $myContainer->parse();
    $myContainer->setTarget('SWF');
    $myContainer->render();
    $myContainer->setTarget('GIF');
    $myContainer->render();
}
