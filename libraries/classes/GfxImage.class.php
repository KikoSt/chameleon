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
    public function __construct(GfxContainer $container)
    {
        parent::__construct($container);
    }


    public function updateData()
    {
        parent::updateData();

        if($this->getContainer()->getProductData())
        {
            if(!empty($this->getRef()))
            {
                $this->setImageUrl($this->getContainer()->getProductData()->getImageUrl());
            }

            if(!empty($this->getLinkUrl()))
            {
                $this->setLinkUrl($this->getContainer()->getProductData()->getProductUrl());
            }
        }
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
            $stroke = new GfxRectangle($this->getContainer());
            $stroke->setWidth($this->getWidth() + ($strokeWidth * 2));
            $stroke->setHeight($this->getHeight() + ($strokeWidth * 2));
            $stroke->setX($this->getX() - $strokeWidth);
            $stroke->setY($this->getY() - $strokeWidth);
            $stroke->setFill($this->getStroke()->getColor());
            $stroke->renderSWF($canvas);

        }

        if($this->getShadowColor() !== null)
        {
            $shadow = new GfxRectangle($this->getContainer());
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

        $now = time();
        $output = $this->resizeImage($this->getImageUrl(), $this->getWidth(), $this->getHeight(), false);
        $then = time();
        echo $this->getId() . ' ---> duration = ' . ($then - $now) . "\n\n";

        ImageJPEG($output, $imgPath, 100);
        imagedestroy($output);
        $output = null;
        unset($output);

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
        if($this->hasShadow())
        {
            $this->createShadow($canvas);
        }

        $dst = $this->resizeImage($this->getImageUrl());

        imagecopyresampled($canvas, $dst, $this->getX(), $this->getY(), 0, 0, $this->getWidth(), $this->getHeight(), $this->getWidth(),
            $this->getHeight());

        return $canvas;
    }

    public function createShadow($canvas)
    {
        $color = imagecolorallocatealpha($canvas,
                                         $this->getShadowColor()->getR(),
                                         $this->getShadowColor()->getG(),
                                         $this->getShadowColor()->getB(),
                                         50
                 );

        $x1 = $this->getX() + $this->getShadowDist();
        $y1 = $this->getY() + $this->getShadowDist();
        $x2 = $x1 + $this->getWidth();
        $y2 = $y1 + $this->getHeight();

        imagefilledrectangle($canvas, $x1, $y1, $x2, $y2, $color);
    }

    public function createStroke($canvas)
    {
        $color = imagecolorallocate($canvas,
            $this->getShadowColor()->getR(),
            $this->getShadowColor()->getG(),
            $this->getShadowColor()->getB()
        );

        $x1 = $this->getX() - $this->getStroke()->getWidth();
        $y1 = $this->getY() - $this->getStroke()->getWidth();
        $x2 = $this->getX() + $this->getStroke()->getWidth() + $this->getWidth();
        $y2 = $this->getY() + $this->getStroke()->getWidth() + $this->getHeight();

        imagefilledrectangle($canvas, $x1, $y1, $x2, $y2, $color);
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
        $aspectRatio = $originalWidth / $originalHeight;

        $newWidth  = $this->getWidth();
        $newHeight = $this->getHeight();

        if($aspectRatio < 1 )
        {
            $newWidth = $newHeight * $aspectRatio;

        }
        else
        {
            $newHeight = $newWidth / $aspectRatio;
        }

        $resizedWidth = $newWidth;
        $resizedHeight = $newHeight;

        $newX = ($this->getWidth() - $newWidth) / 2;
        $newY = ($this->getHeight() - $newHeight) / 2;

        $originalImage = $this->createImageFromSourceFile($file);

        //canvas for resized image
        $resizedImage = imagecreatetruecolor($this->getWidth(), $this->getHeight());

        if(!$resizedImage)
        {
            throw new Exception('Could not create image ' . $this->getId());
        }
        imagealphablending($resizedImage, true);

        $bgcolor = imagecolorallocatealpha($resizedImage, 255, 255, 255, 0);
        imagefill($resizedImage, 0, 0, $bgcolor);

        // imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $originalWidth, $originalHeight);
        imagecopyresampled($resizedImage, $originalImage, $newX, $newY, 0, 0, $resizedWidth, $resizedHeight, $originalWidth, $originalHeight);

        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage,true);

        imagedestroy($originalImage);

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
            default:
            {
                throw new Exception('No valid input file format provided: ' . $extention);
            }
        }
        if(!$image)
        {
            throw new Exception('Could not create image from ' . $file);
        }
        return $image;
    }

    public function getSvg()
    {
        $stroke = $this->getStroke();
        $shadow = $this->getShadowColor();

        $svg = '';
        $svg .= "\r\n" . '<image';
        $svg .= "\r\n" . ' xlink:href="' . str_replace('/var/www/chameleon', '', $this->getImageUrl()) . '"';
        $svg .= "\r\n" . ' linkurl="' . $this->getLinkUrl() . '"';

        if(isset($stroke) && isset($shadow))
        {
            $svg .= "\r\n" . ' style="stroke:' . $stroke->getColor()->getHex() . ';stroke-width:' . $stroke->getWidth() . ';';
            $svg .= ' shadow:' . $this->getShadowColor()->getHex() . ';shadow-dist:' . $this->getShadowDist() . 'px;"';

        }

        $svg .= "\r\n" . ' x="' . $this->getX() . '"';
        $svg .= "\r\n" . ' y="' . $this->getY() . '"';
        $svg .= "\r\n" . ' width="' . $this->getWidth() . '"';
        $svg .= "\r\n" . ' height="' . $this->getHeight() . '"';
        $svg .= "\r\n" . ' id="' . $this->getId() . '"';
        $svg .= "\r\n" . '/>';
        return $svg;
    }

    /**
     * check if file exists and set image url
     *
     * @param $imageUrl
     * @throws FileNotFoundException
     */
    public function setImageUrl($imageUrl)
    {
        if(substr($imageUrl, 0, 4) !== 'http' )
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
