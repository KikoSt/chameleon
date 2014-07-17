<?php

$myContainer = new GfxContainer();

$color = new GfxColor();
$color->setColorHex('#ffcc00');
echo ($color->getColorHex());

$test = new GfxRectangle();
$test->setPosition(10, 200);
$test->create();
$t1 = new GfxRectangle();
$t2 = new GfxRectangle();
$t1->setPosition(10, 200);
$t2->setPosition(123, 815);

$myContainer->addElement($t1);
$myContainer->addElement($t2);

echo $myContainer;


function __autoload($className) {
    if(file_exists('classes/' . $className . '.class.php')) {
        require_once('classes/' . $className . '.class.php');
    } else if(file_exists('interfaces/' . $className . '.interface.php')) {
        require_once('interfaces/' . $className . '.interface.php');
    }
}
