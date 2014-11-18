<?php
Header ("Content-type: image/gif");
$im = imagecreate (400, 30);
$black = ImageColorAllocate ($im, 0, 0, 0);
$white = ImageColorAllocate ($im, 255, 255, 255);
ImageTTFText ($im, 20, 0, 10, 20, $white, "/var/www/chameleon/fonts/ttf/arial.ttf",
    "Teste... Omega: &#937;");
ImageGif ($im);
ImageDestroy ($im);
?>
