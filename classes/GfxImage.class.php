<?php
/**
 * GfxImage
 *
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 11:33
 * @uses GfXComponent
 * @package Gfx
 * @version $id$
 * @copyright 2014 Media Decision
 * @author Christoph 'Kiko' Starkmann <christoph.starkmann@explido.de>
 * @license Proprietary/Closed Source
 */
class GfxImage extends GfXComponent
{
    private $imageUrl;

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * create
     *
     * @param mixed $svgRootNode
     * @access public
     * @return void
     */
    public function create($svgRootNode)
    {
        parent::create($svgRootNode);
        $attr = $svgRootNode->attributes();
        $imageUrl = (string) $svgRootNode->attributes('xlink', true)->href;
        $this->setImageUrl($imageUrl);
        $this->setX((float) $attr->x);
        $this->setY((float) $attr->y);
        $this->setWidth((float) $attr->width);
        $this->setHeight((float) $attr->height);
    }

    /**
     * renderSWF
     *
     * @param mixed $canvas
     * @access public
     * @return mixed $canvas
     */
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

        $bgcolor = imagecolorAllocate($output, 255, 255, 255);
        imagefill($output, 0, 0, $bgcolor);

        ImageCopyResampled($output, $input, 0, $dist, 0, 0, $this->getWidth() + 1, $iHeight + 1, $photoX, $photoY);

        ImageJPEG($output, $imgPath);

        $image = new SWFBitmap(fopen($imgPath, "rb"));
        $handle = $canvas->add($image);
        $handle->moveTo($this->getX(), $this->getY());
        return $canvas;
    }

    public function renderGIF($canvas)
    {
        $dst = $this->resize_image($this->getImageUrl());

        imagecopyresampled($canvas, $dst, $this->getX(), $this->getY(), 0, 0, $this->getWidth(), $this->getHeight(), $this->getWidth(),
            $this->getHeight());

        return $canvas;
    }

    public function resizeImage($file, $crop=false)
    {
        list($originalWidth, $originalHeight) = getimagesize($file);

        $r = $originalWidth / $originalHeight;

        $resizedWidth = $this->getWidth();
        $resizedHeight = $this->getHeight();

        if ($crop)
        {
            if ($originalWidth > $originalHeight)
            {
                $originalWidth = ceil($originalWidth-($originalWidth*abs($r-($resizedWidth / $resizedHeight))));
            }
            else
            {
                $originalHeight = ceil($originalHeight-($originalHeight*abs($r-($resizedWidth / $resizedHeight))));
            }
        }
        else
        {
            if (($resizedWidth/$resizedHeight) > $r)
            {
                $resizedWidth *= $r;
            }
            else
            {
                $resizedWidth /= $r;
            }
        }

        $x = ($this->getWidth()-$resizedWidth) / 2;

        $originalImage = imagecreatefromjpeg($file);

        //canvas for resized image
        $resizedImage = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        //adding resized image () to the canvas

        $white = imagecolorallocate($resizedImage, 255, 255, 255);
        imagefill($resizedImage, 0, 0, $white);

        imagecopyresampled($resizedImage, $originalImage, $x, 0, 0, 0, $resizedWidth, $resizedHeight, $originalWidth, $originalHeight);

        return $resizedImage;
    }

    /**
     * setImageUrl
     *
     * @param mixed $imageUrl
     * @access public
     * @return void
     */
    public function setImageUrl($imageUrl)
    {
        if(fopen($imageUrl, "r"))
        {
            $this->imageUrl = $imageUrl;
        }
        else
        {
            throw new FileNotFoundException($imageUrl);
        }

//        $fileHeaders = get_headers($imageUrl);
//        // TODO: make this more robust!!!
//        if(substr($fileHeaders[0], -6) == '200 OK')
//        {
//            $this->imageUrl = $imageUrl;
//        }
//        else
//        {
//
//        }
    }

    /**
     * getImageUrl
     *
     * @access public
     * @return string $imageUrl
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }
}
