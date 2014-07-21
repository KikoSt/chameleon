<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 11:33
 */

class GfxImage extends GfXComponent
{
    private $imageUrl;

    public function __construct()
    {
        parent::__construct();
    }


    public function create($svgRootNode)
    {
        parent::create($svgRootNode);
        $attr = $svgRootNode->attributes();
        $imageUrl = (string) $svgRootNode->attributes('xlink', true)->href;
        $this->setImageUrl($imageUrl);
        $this->setWidth(1);
        $this->setHeight(1);
    }

    public function renderSWF($canvas)
    {
        $imgPath = 'tmp/file.jpg';

        copy($this->getImageUrl(), $imgPath);

        $size = GetImageSize($imgPath);
        $iHeight=round($this->getWidth() * $size[1] / $size[0]);

        $input = ImageCreateFromJPEG($imgPath);
        $photoX = ImagesX($input);
        $photoY = ImagesY($input);

        $dist = ($this->getWidth() - $iHeight) / 2;

        $output = ImageCreateTrueColor($this->getWidth(), $this->getHeight());

        $bg = imagecolorAllocate($output, 255, 255, 255);
        imagefill($output, 0, 0, $bg);

        ImageCopyResampled($output, $input, 0, $dist, 0, 0, $this->getWidth() + 1, $iHeight + 1, $photoX, $photoY);

        ImageJPEG($output, $imgPath);

        $image = new SWFBitmap(fopen($imgPath, "rb"));
        // $handle = $canvas->add($image);
        // $handle->moveTo($this->getX(), $this->getY());
        // $handle->moveTo(0, 0);
        return $canvas;
    }

    public function setImageUrl($imageUrl)
    {
        $fileHeaders = get_headers($imageUrl);
        // TODO: make this more robust!!!
        if(substr($fileHeaders[0], -6) == '200 OK') {
            $this->imageUrl = $imageUrl;
        } else {
            echo 'File not found' . "\n";
            return false;
        }
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }
}
