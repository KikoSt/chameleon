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

$width = 800;
$height = 340;

//create a canvas with the background image
$image = new GDEnhancer(GIFPROTO . 'gifAnimationTestbackground.jpg');
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
file_put_contents(GIFPROTO . 'gtBackground'.'.'.$save['extension'], $save['contents']);

// create a transparent canvas
$layer = imagecreate($width, $height);
$color = imagecolorallocatealpha($layer, 0, 0, 0, 127);
imagefill($layer, 0, 0, $color);
imagesavealpha($layer, TRUE);
imagepng($layer, GIFPROTO . 'layer.png');

$imageLayer1 = new GDEnhancer(GIFPROTO . 'layer.png');
$imageLayer1->layerImage(GIFPROTO . 'gifAnimationTestlogo.png');
$imageLayer1->layerMove(0, 'bottomleft', 0, 0);
$imageLayer1->layerImageResize(0, 1, 1, 'fill');
$saveLayer1 = $imageLayer1->save();
file_put_contents(GIFPROTO . 'gtLayer1'.'.'.$saveLayer1['extension'], $saveLayer1['contents']);

$imageLayer2 = new GDEnhancer(GIFPROTO . 'layer.png');
$imageLayer2->layerImage(GIFPROTO . 'gifAnimationTestlogo.png');
$imageLayer2->layerMove(0, 'bottomleft', 0, 0);
$imageLayer2->layerImageResize(0, 43, 26, 'fill');
$saveLayer2 = $imageLayer2->save();
imagealphablending( $imageLayer2, false );
imagesavealpha( $imageLayer2, true );
file_put_contents(GIFPROTO . 'gtLayer2'.'.'.$saveLayer2['extension'], $saveLayer2['contents']);

$imageLayer3 = new GDEnhancer(GIFPROTO . 'layer.png');
$imageLayer3->layerImage(GIFPROTO . 'gifAnimationTestlogo.png');
$imageLayer3->layerMove(0, 'bottomleft', 0, 0);
$imageLayer3->layerImageResize(0, 85, 55, 'fill');
$saveLayer3 = $imageLayer3->save();
imagealphablending( $imageLayer3, false );
imagesavealpha( $imageLayer3, true );
file_put_contents(GIFPROTO . 'gtLayer3'.'.'.$saveLayer3['extension'], $saveLayer3['contents']);

ob_start();
imagegif(imagecreatefromjpeg(GIFPROTO . 'gtBackground.jpg'));
$frames[]=ob_get_contents();
$framed[]=40;
ob_end_clean();

ob_start();
$png = imagecreatefrompng(GIFPROTO . 'gtLayer1.png');
imagealphablending( $png, false );
imagesavealpha( $png, true );
imagegif($png);
$frames[]=ob_get_contents();
$framed[]=40;
ob_end_clean();

ob_start();
imagegif(imagecreatefrompng(GIFPROTO . 'gtLayer2.png'));
$frames[]=ob_get_contents();
$framed[]=40;
ob_end_clean();

ob_start();
imagegif(imagecreatefrompng(GIFPROTO . 'gtLayer3.png'));
$frames[]=ob_get_contents();
$framed[]=40;
ob_end_clean();

//addAnimation('gtBackground.jpg');
//addAnimation('gtLayer1.png');
//addAnimation('gtLayer2.png');
//addAnimation('gtLayer3.png');

$gif = new GIFEncoder($frames,$framed,0,2,0,0,0,'bin');

$fp = fopen(GIFPROTO . 'gtAnimated.gif', 'w');
fwrite($fp, $gif->GetAnimation());
fclose($fp);

exec('chmod -R 0777 /var/www/chameleon/assets/gifProto/');

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