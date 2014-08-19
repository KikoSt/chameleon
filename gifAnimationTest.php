<?php
/**
 * To find out, how gif animations work
 */

require_once('Bootstrap.php');
include('config/pathconfig.inc.php');
include('config/fontconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

//$container = new GfxContainer();

//parse the svg
//$container->setSource('bumblebee.svg');
//$container->parse();

include_once 'libraries/gdenhancer/GDEnhancer.php';

use \gdenhancer\GDEnhancer;

//adding the background image (canvas)
$image = new GDEnhancer('assets/gifAnimationTest.jpg');
$image->backgroundResize(800, 800, 'shrink');

//adding a slogan
$text = <<<RAIN
Warlords of Dreanor
RAIN;
$font = $fontlist['GIF']['BITSTREAM_VERA_SANS_MONO'];
$image->layerText($text, $font, 28, '#FFF', 0, 0.7); //This is layer 0
$image->layerMove(0, 'center', 0, 0);

//adding a logo, set to layer 1
$image->layerImage('assets/gifAnimationTestlogo.png');
$image->layerMove(1, 'bottomleft', 0, 0);
$image->layerImageResize(1, 1, 1, 'fill');

$save = $image->save();

$image->saveTo('assets/bumblebee01');

// Writing file
file_put_contents('assets/bumblebee02'.'.'.$save['extension'], $save['contents']);



header('content-type:' . $save['mime']);
echo $save['contents'];