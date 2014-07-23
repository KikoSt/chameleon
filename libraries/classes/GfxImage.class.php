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

        $output = $this->resizeImage($this->getImageUrl(), $this->getWidth(), $this->getHeight(), false);

        ImageJPEG($output, $imgPath);

        $image = new SWFBitmap(fopen($imgPath, "rb"));
        $handle = $canvas->add($image);
        $handle->moveTo($this->getX(), $this->getY());
        return $canvas;
    }

    /**
     * render GIF
     *
     * @param $canvas
     * @return mixed
     */
    public function renderGIF($canvas)
    {
        $dst = $this->resizeImage($this->getImageUrl(),true);

        imagecopyresampled($canvas, $dst, $this->getX(), $this->getY(), 0, 0, $this->getWidth(), $this->getHeight(), $this->getWidth(),
            $this->getHeight());

        return $canvas;
    }

    /**
     * resize image
     *
     * @param $file
     * @param bool $crop
     * @return resource
     */
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
                $resizedWidth = $this->getHeight() * $r;
            }
            else
            {
                $resizedWidth = $this->getWidth() / $r;
            }
        }

        $x = ($this->getWidth()-$resizedWidth) / 2;

        $originalImage = $this->createImageFromSourceFile($file);

        Debug::console($originalImage);

        //canvas for resized image
        $resizedImage = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        imagealphablending($resizedImage, true);

        $bgcolor = imagecolorallocatealpha($resizedImage, 255, 255, 255, 0);
        imagefill($resizedImage, 0, 0, $bgcolor);

        imagecopyresampled($resizedImage, $originalImage, $x, 0, 0, 0, $resizedWidth, $resizedHeight, $originalWidth, $originalHeight);
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage,true);

        return $resizedImage;
    }

    private function createImageFromSourceFile($file)
    {
        list( $dirname, $basename, $extension, $filename ) = array_values( pathinfo($file) );

        $image = null;

        switch($extension)
        {
            case "jpg":
            {
                $image = imagecreatefromjpeg($file);
                break;
            }
            case "png":
            {
                $image = imagecreatefrompng($file);
                break;
            }
            case "gif":
            {
                $image = imagecreatefromgif($file);
                break;
            }
        }
        return $image;
    }

    /**
     * check if file exists and set image url
     *
     * @param $imageUrl
     * @throws FileNotFoundException
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
