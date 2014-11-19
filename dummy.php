<?php

$gif = new Imagick();
$gif->setFormat('gif');

$bg = new Imagick('8bit.png');
$gif->setGravity(imagick::GRAVITY_CENTER);
$gif->addImage($bg);

for($i=0; $i<40; $i++)
{
$frame = new Imagick('image.png');
$frame->rotateImage(new ImagickPixel('none'), (360/40)*$i);
$frame->extentImage(300, 300, -100, -70);
$frame->setImageDelay(1);
// IMPORTANT! Clean up animation mess!
$frame->setImageDispose(3);
$gif->addImage($frame);
}


for($i=0; $i<40; $i++)
{
$frame = new Imagick('image.png');
$frame->rotateImage(new ImagickPixel('none'), -(360/40)*$i);
$frame->extentImage(300, 300, -100, -70);
$frame->setImageDelay(100);
// IMPORTANT! Clean up animation mess!
$frame->setImageDispose(3);
$gif->addImage($frame);
}


header('Content-type: image/gif');
echo $gif->getImagesBlob();
// header('Content-type: image/gif');

exit();



echo 'Writing file';
try
{
$result = $gif->writeImages('assets/animation.gif', true);
}
catch(Exception $e)
{
var_dump($e);
}
// echo $gif->getImagesBlob();

var_dump($result);

?>
<img src="assets/animation.gif" />
<?php

exit(0);