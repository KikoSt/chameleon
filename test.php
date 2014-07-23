<?php

$myContainer = new GfxContainer();
$myContainer->setSource('svg/ttest_3.svg');
$myContainer->parse();
$myContainer->setTarget('SWF');
$myContainer->render();
$myContainer->setTarget('GIF');
$myContainer->render();

function __autoload($className) {
    if(file_exists('libraries/classes/' . $className . '.class.php')) {
        require_once('libraries/classes/' . $className . '.class.php');
    } else if(file_exists('libraries/interfaces/' . $className . '.interface.php')) {
        require_once('libraries/interfaces/' . $className . '.interface.php');
    }
}
