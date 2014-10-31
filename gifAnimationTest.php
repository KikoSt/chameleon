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
include_once 'libraries/gdenhancer/GDEnhancer.php';

use \gdenhancer\GDEnhancer;

$width = 850;//$container->getCanvasWidth();
$height = 500;//$container->getCanvasHeight();

//create a canvas with the background image
$image = new GDEnhancer(GIFPROTO . '/gifAnimationTestbackground.jpg');
$image->backgroundResize($width, $height, 'shrink');

//add static elements
//adding a slogan
$text = <<<RAIN
Warlords of Dreanor
RAIN;
$font = $fontlist['GIF']['BITSTREAM_VERA_SANS_MONO'];
$image->layerText($text, $font, 28, '#FFF', 0, 0.7); //This is layer 0
$image->layerMove(0, 'center', 0, 0);
$save = $image->save();
file_put_contents(GIFPROTO . '/gtBackground'.'.'.$save['extension'], $save['contents']);

// create a transparent canvas
//$layer = imagecreate($width, $height);
//$color = imagecolorallocatealpha($layer, 0, 0, 0, 127);
//imagefill($layer, 0, 0, $color);
//imagesavealpha($layer, TRUE);
//imagepng($layer, GIFPROTO . '/layer.png');

//create a gif with n layers
$n = 10;

for($i=1; $i <= $n; $i++)
{
    list($width, $height, $type, $attr) = getimagesize(GIFPROTO . '/gifAnimationTestlogo.png');

    $imageLayer = new GDEnhancer(GIFPROTO . '/layer.png');
    $imageLayer->layerImage(GIFPROTO . '/gifAnimationTestlogo.png');
    $imageLayer->layerMove(0, 'bottomleft', 0, 0);
    $imageLayer->layerImageResize(0, 1, 1, 'fill');

    if($i > 1 && $i !== $n)
    {
        $width = ($width / $n) * $i;
        $height = ($height / $n) * $i;

        $imageLayer->layerImageResize(0, $width, $height, 'fill');
    }

    if($i === $n)
    {
        $imageLayer->layerImageResize(0, $width, $height, 'fill');
    }


    $saveLayer = $imageLayer->save();
    file_put_contents(GIFPROTO . '/gtLayer'.$i.'.'.$saveLayer['extension'], $saveLayer['contents']);
}

ob_start();
imagegif(imagecreatefromjpeg(GIFPROTO . '/gtBackground.jpg'));
$frames[]=ob_get_contents();
$framed[]=40;
ob_end_clean();


//for($i=1; $i > $n; $i++)
//{
//    ob_start();
//    $png = imagecreatefrompng(GIFPROTO . '/gtLayer'.$i.'.png');
//
//    if($i === 1)
//    {
//        imagealphablending( $png, false );
//        imagesavealpha( $png, true );
//        imagegif($png);
//    }
//    $frames[]=ob_get_contents();
//    $framed[]=40;
//    ob_end_clean();
//}



ob_start();
$png = imagecreatefrompng(GIFPROTO . '/gtLayer1.png');
imagealphablending( $png, false );
imagesavealpha( $png, true );
imagegif($png);
$frames[]=ob_get_contents();
$framed[]=40;
ob_end_clean();

ob_start();
imagegif(imagecreatefrompng(GIFPROTO . '/gtLayer2.png'));
$frames[]=ob_get_contents();
$framed[]=40;
ob_end_clean();

ob_start();
imagegif(imagecreatefrompng(GIFPROTO . '/gtLayer3.png'));
$frames[]=ob_get_contents();
$framed[]=40;
ob_end_clean();


$gif = new GIFEncoder($frames,$framed,0,2,0,0,0,'bin');

$fp = fopen(GIFPROTO . '/gtAnimated.gif', 'w');
fwrite($fp, $gif->GetAnimation());
fclose($fp);

header ('Content-type:image/gif');
echo $gif->GetAnimation();

function addAnimation($filename)
{
    switch(pathinfo($filename, PATHINFO_EXTENSION))
    {
        case 'jpg':
        {
            $image = imagecreatefromjpeg(ASSET_DIR.$filename);
            break;
        }
        case 'png':
        {
            $image = imagecreatefrompng(ASSET_DIR.$filename);
            break;
        }
    }

    ob_start();
    imagegif($image);
    $frames[]=ob_get_contents();
    $framed[]=40;

    ob_end_clean();
}

function createFrame()
{

}