<?php

$sizes = array();

$sizes[] = array(300, 100);
$sizes[] = array(728, 90);
$sizes[] = array(468, 60);
$sizes[] = array(234, 60);
$sizes[] = array(120, 240);
$sizes[] = array(300, 250);
$sizes[] = array(750, 300);
$sizes[] = array(88, 31);
$sizes[] = array(120, 60);
$sizes[] = array(240, 400);
$sizes[] = array(300, 600);
$sizes[] = array(160, 600);
$sizes[] = array(120, 600);
$sizes[] = array(250, 250);
$sizes[] = array(336, 280);
$sizes[] = array(180, 150);
$sizes[] = array(125, 125);

$bannerSizes = array();

foreach($sizes AS $size)
{
    $curBanner = new StdClass();
    $curBanner->width  = $size[0];
    $curBanner->height = $size[1];
    $bannerSizes[] = $curBanner;
}
