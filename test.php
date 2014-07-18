<?php

$myContainer = new GfxContainer();
$myContainer->setSource('svg/test.svg');
$myContainer->parse();



//$color = new GfxColor();
//$color->setHex('#ffcc00');
//$color->setRGB(0,204,204);
//
//$test = new GfxRectangle();
//$test->setPosition(10, 200);
//$test->create();
//$t1 = new GfxRectangle();
//$t2 = new GfxRectangle();
//$t1->setPosition(10, 200);
//$t2->setPosition(123, 815);
//
//$myContainer->addElement($t1);
//$myContainer->addElement($t2);
//
//$oText = new GfxText($color);
//$oText->setId("headline");
//$oText->setText("das ist ein toller text");
//$oText->setFont(new SWFFont('fdb/bvs.fdb'));
//$oText->setColor($color);
//$oText->setHeight(10);
//$oText->setPosition($oText->getWidth(), 0);
//$oText->create();

//Debug::browser($oText, true);

function __autoload($className) {
    if(file_exists('classes/' . $className . '.class.php')) {
        require_once('classes/' . $className . '.class.php');
    } else if(file_exists('interfaces/' . $className . '.interface.php')) {
        require_once('interfaces/' . $className . '.interface.php');
    }
}
