<?php

$myContainer = new GfxContainer();
$myContainer->setSource('svg/test.svg');
$myContainer->parse();
$myContainer->setTarget('SWF');
$myContainer->render();
$myContainer->setTarget('GIF');
$myContainer->render();

function __autoload($className) {
    if(file_exists('classes/' . $className . '.class.php')) {
        require_once('classes/' . $className . '.class.php');
    } else if(file_exists('interfaces/' . $className . '.interface.php')) {
        require_once('interfaces/' . $className . '.interface.php');
    }
}
