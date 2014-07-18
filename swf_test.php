<?php

$myContainer = new GfxContainer();

// ALL THE FOLLOWING CODE WILL GO INTO THE CONTAINER ASAP
$c1 = new GfxColor();
$c2 = new GfxColor();
$c3 = new GfxColor();
$c1->setHex('#ffcc00');
$c2->setHex('#ffffff');
$c3->setHex('#000000');

$r1 = new GfxRectangle();
$r2 = new GfxRectangle();

$canvasWidth = 160;
$canvasHeight = 500;

$r1->setWidth($canvasWidth);
$r1->setHeight($canvasHeight);
$r1->setPosition(0, 0);

$r2->setPosition(12.5, 12.5);
$r2->setWidth(135);
$r2->setHeight(475);

$r1->setColor($c1);
$r2->setColor($c2);

$t1 = new GfxText();
$t1->setId("headline");
$t1->setText("Globetrotter");
$t1->setFont(new SWFFont('fdb/bvs.fdb'));
$t1->setColor($c3);
$t1->setHeight(10);
$t1->setPosition(($canvasWidth / 2) - ($t1->getTextWidth() / 2), $canvasHeight - 25);

$t2 = new GfxText();
$t2->setId("tagline1");
$t2->setText("get active");
$t2->setFont(new SWFFont('fdb/bvs.fdb'));
$t2->setColor($c1);
$t2->setHeight(20);
$t2->setPosition(($canvasWidth / 2) - ($t2->getTextWidth() / 2), $canvasHeight - 175);

$t3 = new GfxText();
$t3->setId("tagline2");
$t3->setText("NOW!");
$t3->setFont(new SWFFont('fdb/bvs.fdb'));
$t3->setColor($c3);
$t3->setHeight(28);
$t3->setPosition(($canvasWidth / 2) - ($t3->getTextWidth() / 2), $canvasHeight - 125);

$myContainer->setCanvasSize($canvasWidth, $canvasHeight);

$myContainer->addElement($r1);
$myContainer->addElement($r2);
$myContainer->addElement($t1);
$myContainer->addElement($t2);
$myContainer->addElement($t3);
// END OF CONTAINER CODE


$myContainer->setTarget('SWF');
$myContainer->render();

// Debug::console($myContainer);




function __autoload($className) {
    if(file_exists('classes/' . $className . '.class.php')) {
        require_once('classes/' . $className . '.class.php');
    } else if(file_exists('interfaces/' . $className . '.interface.php')) {
        require_once('interfaces/' . $className . '.interface.php');
    }
}
