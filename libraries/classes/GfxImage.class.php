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
        if((string) $svgRootNode->attributes()->linkurl !== '')
        {
            $this->setLinkUrl((string) $svgRootNode->attributes()->linkurl);
        }
        $this->setImageUrl($imageUrl);
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
        if($this->getStroke() !== null)
        {
            $strokeWidth = $this->getStroke()->getWidth();
            $stroke = new GfxRectangle();
            $stroke->setWidth($this->getWidth() + ($strokeWidth * 2));
            $stroke->setHeight($this->getHeight() + ($strokeWidth * 2));
            $stroke->setX($this->getX() - $strokeWidth);
            $stroke->setY($this->getY() - $strokeWidth);
            $stroke->setFill($this->getStroke()->getColor());
            $stroke->renderSWF($canvas);

        }

        if($this->getShadowColor() !== null)
        {
            $shadow = new GfxRectangle();
            $shadow->setWidth($this->getWidth());
            $shadow->setHeight($this->getHeight());
            $shadow->setX($this->getX() + (int) $this->getShadowDist());
            $shadow->setY($this->getY() + (int) $this->getShadowDist());
            $shadowColor = $this->getShadowColor();
            $shadowColor->setAlpha(128);
            $shadow->setFill($shadowColor);
            $shadow->renderSWF($canvas);

        }
        $imgPath = 'tmp/file' . time() . rand() . '.jpg';

        $output = $this->resizeImage($this->getImageUrl(), $this->getWidth(), $this->getHeight(), false);

        ImageJPEG($output, $imgPath);

        $image = new SWFBitmap(fopen($this->getImageUrl(), "rb"));
        $image = new SWFBitmap(fopen($imgPath, "rb"));
        $handle = $canvas->add($image);
        $handle->moveTo($this->getX(), $this->getY());
        $canvas = $this->addClickableLink($canvas);
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
        $dst = $this->resizeImage($this->getImageUrl());

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

        if($originalWidth>$originalHeight)
        {
            $crop = true;
        }

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

        //canvas for resized image
        $resizedImage = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        imagealphablending($resizedImage, true);

        $bgcolor = imagecolorallocatealpha($resizedImage, 255, 255, 255, 0);
        imagefill($resizedImage, 0, 0, $bgcolor);

        imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $originalWidth, $originalHeight);
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
        if(substr($imageUrl, 0, 4) !== 'http')
        {
            $imageUrl = ROOT_DIR . $imageUrl;
        }
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
