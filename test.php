<?php

$myElem = new GfxComponent();
$myAni = new GfxAnimation($myElem);
$myAni->setFramerate(10);

function __autoload($className) {
    if(file_exists('libraries/classes/' . $className . '.class.php')) {
        require_once('libraries/classes/' . $className . '.class.php');
    } else if(file_exists('libraries/interfaces/' . $className . '.interface.php')) {
        require_once('libraries/interfaces/' . $className . '.interface.php');
    }
}

?>

