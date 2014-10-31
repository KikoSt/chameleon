<?php
/**
 * To find out, how gif animations work
 */

$ani = new Imagick();
$ani->setFormat('gif');
$ani->newImage(200, 100, new ImagickPixel('yellow'));

$color = new ImagickPixel('white');
$color->setColor('white');
// $color->setColor('none');

$string = 'mal sehen';
$draw = new ImagickDraw();
$draw->setFont('Helvetica');

$draw->setGravity(Imagick::GRAVITY_CENTER);
// $ani->setImageDispose(2);

$draw->setFillColor('wheat');
$draw->setStrokeColor(new ImagickPixel('green'));
$draw->rectangle(20, 20, 90, 40);

// reset stroke color to BLACK
$draw->setStrokeColor(new ImagickPixel('black'));

$rotation = -22.5;

for ($i = 0; $i <=9; $i++)
{
    /*** create a new gif frame ***/
    $ani->newImage(200, 100, $color);
    $draw->rotate(25);

    $rotation += 5;

    /*** add the character to the image ***/
    $ani->annotateImage($draw, 10, 10, $rotation, $string);

    /*** set the frame delay to 30 ***/
    $ani->setImageDelay(5);
}
$ani->drawImage($draw);

for ($i = 0; $i<=9 ; $i++ )
{
    /*** create a new gif frame ***/
    $ani->newImage(100, 50, $color);
    $draw->rotate(-25);
    $ani->drawImage($draw);

    $rotation -= 5;

    /*** add the character to the image ***/
    $ani->annotateImage($draw, 10, 10, $rotation, $string);

    /*** set the frame delay to 30 ***/
    $ani->setImageDelay(5);
}


/*** write the file ***/
$out = true;

$path = "/var/www/chameleon/assets/gifProto/ani.gif";

$ani->writeImages($path, $out);

echo('<image src="http://localhost/chameleon/assets/gifProto/ani.gif"></image>');
