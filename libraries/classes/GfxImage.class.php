<?php
/**
 * GfxImage
 *
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
        $imageUrl = (string) $svgRootNode->attributes('xlink', true)->href;
        if((string) $svgRootNode->attributes()->linkurl !== '')
        {
            $this->setLinkUrl((string) $svgRootNode->attributes()->linkurl);
        }

        $imageUrl = str_replace("//assets", "/assets", $imageUrl);

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
        $sprite = new SWFSprite();
        $sprite->setFrames($this->getContainer()->getFramerate());

        if($this->getStroke() !== null)
        {
            // echo 'Stroke: ' . $this->getId() . "\n";
            $stroke = new SWFShape();
            $stroke->setLine(1, 0, 0, 0);
            $strokeX1 = -($this->getWidth()  / 2) - $this->getStroke()->getWidth();
            $strokeY1 = -($this->getHeight() / 2) - $this->getStroke()->getWidth();
            $strokeX2 = ($this->getWidth()   / 2) + $this->getStroke()->getWidth();
            $strokeY2 = ($this->getHeight()  / 2) + $this->getStroke()->getWidth();

            $strokeColor = $this->getstroke()->getColor();
            $strokeFill = $stroke->addFill($strokeColor->getR(), $strokeColor->getG(), $strokeColor->getB(), 255);
            $stroke->setRightFill($strokeFill);

            $stroke->movePenTo($strokeX1, $strokeY1);
            $stroke->drawLineTo($strokeX1, $strokeY2);
            $stroke->drawLineTo($strokeX2, $strokeY2);
            $stroke->drawLineTo($strokeX2, $strokeY1);
            $stroke->drawLineTo($strokeX1, $strokeY1);

            $sHandle = $sprite->add($stroke);
        }

        $localX1 = -($this->getWidth() / 2);
        $localY1 = -($this->getHeight() / 2);
        $localX2 = $localX1 + $this->getWidth();
        $localY2 = $localY1 + $this->getHeight();

        $globalX1 = $this->getX() - $localX1;
        $globalY1 = $this->getY() - $localY1;
        $globalX2 = $globalX1 + $this->getWidth();
        $globalY2 = $globalY1 + $this->getHeight();


        if($this->shadowEnabled() && $this->getShadow()->getColor() instanceof GfxColor)
        {
            $shadow = new SWFShape();
            $shadowX1 = $localX1 + $this->getShadow()->getDist();
            $shadowY1 = $localY1 + $this->getShadow()->getDist();
            $shadowX2 = $localX2 + $this->getShadow()->getDist();
            $shadowY2 = $localY2 + $this->getShadow()->getDist();

            $shadowColor = $this->getShadow()->getColor();
            $shadowFill = $shadow->addFill($shadowColor->getR(), $shadowColor->getG(), $shadowColor->getB(), 128);
            $shadow->setRightFill($shadowFill);

            $shadow->movePenTo($shadowX1, $shadowY1);
            $shadow->drawLineTo($shadowX1, $shadowY2);
            $shadow->drawLineTo($shadowX2, $shadowY2);
            $shadow->drawLineTo($shadowX2, $shadowY1);
            $shadow->drawLineTo($shadowX1, $shadowY1);

            $shadowHandle = $sprite->add($shadow);
        }

        $imgPath = '/tmp/file' . time() . rand() . '.jpg';

        $output = $this->resizeImage($this->getImageUrl(), $this->getWidth(), $this->getHeight(), false);

        $result = imagejpeg($output, $imgPath, 100);
        imagedestroy($output);
        $output = null;
        unset($output);

        $bastardImage = fopen($imgPath, "rb");

        $image  = new SWFBitmap($bastardImage);
        $isprite = new SWFSprite();
        $ihandle = $isprite->add($image);
        $ihandle->moveTo($localX1, $localY1);
        $handle = $sprite->add($isprite);
        $isprite->nextFrame();
        $handle->moveTo(0, 0);

        if(false !== ($lhandle = $this->addClickableLink($sprite)))
        {
            // $handle = $canvas->add($lsprite);
        }
        if($this->drawCenter)
        {
            $chandle = $this->drawCenter($sprite);
        }

        /**
         *  Prepare actual animation
        **/

        if(count($this->getAnimations()) != 0)
        {
            $handleList = array();
            if(isset($lhandle))
            {
                $handleList['linkHandle'] = $lhandle;
            }
            if(isset($sHandle))
            {
                $handleList['sHandle'] = $sHandle;
            }
            if(isset($shadowHandle))
            {
                $handleList['shadowHandle'] = $shadowHandle;
            }
            $handleList['handle'] = $handle;
            $sprite = $this->swfAnimate($handleList, $sprite);
        }
        /**
         *  Animation done!
        **/

        $this->getContainer()->register($bastardImage);

        $handle = $canvas->add($sprite);
        $handle->moveTo($this->getX() + ($this->getWidth() / 2), $this->getY() + ($this->getHeight() / 2));
        $handle->moveTo($globalX1, $globalY1);
        $sprite->nextFrame();

        unset($image);
        return $canvas;
    }

    /**
     * render GIF
     *
     * @param $canvas
     * @return mixed
     */
    public function renderGIF($transformationList = null, $skip = false)
    {
        if(!isset($this->gifParams))
        {
            $this->gifParams = new GifAnimationContainer($this);
        }

        foreach($transformationList AS $attribute => $stepsize)
        {
            // echo $this->getId() . ': ' . $attribute . ': ' . $stepsize . "\n";
            $stepsize = $stepsize;
            switch($attribute)
            {
                case 'x':
                    $this->gifParams->x += $stepsize;
                    break;
                case 'y':
                    $this->gifParams->y += $stepsize;
                    break;
                case 'w':
                    $this->gifParams->width *= $stepsize;
                    break;
                case 'h':
                    $this->gifParams->height *= $stepsize;
                    break;
                case 'r':
                    $this->gifParams->rotation += $stepsize;
                    break;
                default:
                    break;
            }
        }

        if($skip)
        {
            return true;
        }

        $pos = strpos($this->getImageUrl(), 'http');

        if($pos === false)
        {
            $filepath = ROOT_DIR . $this->getImageUrl();
        }
        else
        {
            $filepath = $this->getImageUrl();
        }

        $filepath = str_replace('%20' , ' ', $filepath);

        $transparent = new ImagickPixel("rgba(127,127,127,0)");

        $image = new Imagick($filepath);

        $imageWidth  = $this->getContainer()->getCanvasWidth();
        $imageHeight = $this->getContainer()->getCanvasHeight();

        $frame = new Imagick();
        $frame->newImage($imageWidth, $imageHeight, $transparent);

        // $frameDraw = new ImagickDraw();

        // $image->resizeimage($this->getWidth(), $this->getHeight(), imagick::FILTER_BOX, 0.2, true);
        $image->scaleimage($this->gifParams->width, $this->gifParams->height, false);

        if($this->hasShadow() && $this->shadowEnabled())
        {
            $shadow = $this->createShadow();
            $frame->drawImage($shadow);
        }

        if($this->hasStroke() && $this->strokeEnabled())
        {
            $this->createStroke($image);
        }

        $x = $this->gifParams->x + ($this->getWidth() - $this->gifParams->width) / 2;
        $y = $this->gifParams->y + ($this->getHeight() - $this->gifParams->height) / 2;

        $width = $this->gifParams->width;
        $height = $this->gifParams->height;
        $rotation = $this->gifParams->rotation;

        $distort = array($width/2, $height /2, 1, 1, -$rotation, $x + $width / 2, $y + $height / 2);

        $frame->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);

        $frame->compositeImage($image, Imagick::COMPOSITE_DEFAULT, 0, 0); // $width / 2, $height / 2);
        $frame->distortImage(imagick::DISTORTION_SCALEROTATETRANSLATE, $distort, false);

        return $frame;
    }

    public function createShadow()
    {
        $color = new ImagickPixel($this->getShadow()->getColor()->getHex());

        $x1 = $this->getShadow()->getDist();
        $y1 = $this->getShadow()->getDist();

        $x2 = $x1 + $this->gifParams->width;
        $y2 = $y1 + $this->gifParams->height;

        $shadow = new ImagickDraw();
        $shadow->setFillColor($color);
        $shadow->setfillopacity(0.5);
        $shadow->rectangle($x1, $y1, $x2, $y2);

        return $shadow;
    }

    public function createStroke($image)
    {
        $width =  $this->getStroke()->getWidth(); // $this->getWidth() + ($this->getStroke()->getWidth() * 2);
        $height = $this->getStroke()->getWidth();  //  $this->getHeight() + ($this->getStroke()->getHeight() * 2);
        $image->borderimage($this->getStroke()->getColor()->getHex(), $width, $height);
    }


    /**
     * resize image
     *
     * using GD! this should be changed at some point, too, and we need a similar method for the gif creatives rendering
     *
     * @param $file
     * @param bool $crop
     * @return resource
     */
    public function resizeImage($file, $crop=false)
    {
        $filepath = $this->getFilepath($file);
        if(false === file_get_contents($filepath, 0, null, 0, 1))
        {
            $file = 'assets/image_not_found.jpg';
            throw new Exception('Could not open file ' . $filepath);
        }

        list($originalWidth, $originalHeight) = getimagesize($filepath);

        if($originalWidth <= 0 || $originalHeight <=0)
        {
            throw new Exception('Getting file ' . $file . ' failed; Dimensions <= zero found');
        }
        $aspectRatio = $originalWidth / $originalHeight;

        $newWidth  = $this->getWidth();
        $newHeight = $this->getHeight();

        $cachedFile = IMGCACHE_DIR . '/' . $this->getContainer()->getOutputDir() . '/' . urlencode($file);
        if(file_exists($cachedFile))
        {
            $file = $cachedFile;
        }

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

        // massively time consuming
        if($this->getContainer()->getPreviewMode() !== true)
        {
            imagecopyresampled($resizedImage, $originalImage, $newX, $newY, 0, 0, $resizedWidth, $resizedHeight, $originalWidth, $originalHeight);
        }
        else
        {
            imagecopyresized($resizedImage, $originalImage, $newX, $newY, 0, 0, $resizedWidth, $resizedHeight, $originalWidth, $originalHeight);
        }

        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage,true);

        imagedestroy($originalImage);

        return $resizedImage;
    }

    private function createImageFromSourceFile($file)
    {
        list( $dirname, $basename, $extension, $filename ) = array_values(pathinfo($file));
        unset($dirname);
        unset($basename);
        unset($filename);

        $image = null;
        $extension = strtolower($extension);

        $filepath = $this->getFilepath($file);

        switch($extension)
        {
            case "jpg":
            case "jpeg":
            {
                $image = imagecreatefromjpeg($filepath);
                break;
            }
            case "png":
            {
                $image = imagecreatefrompng($filepath);
                break;
            }
            case "gif":
            {
                $image = imagecreatefromgif($filepath);
                break;
            }
            default:
            {
                throw new Exception('No valid input file format provided: ' . $extension);
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
        $shadow = $this->getShadow();

        $svg = '';
        $svg .= "\r\n" . '<image';
        $svg .= "\r\n" . ' cmeo:ref="' . $this->getCmeoRef(). '"';
        $svg .= "\r\n" . ' cmeo:link="' . $this->getCmeoLink(). '"';
        $svg .= "\r\n" . ' cmeo:editGroup="' . $this->getEditGroup(). '"';

        if(count($this->getAnimations()) > 0)
        {
            $aniString  = "\r\n" . ' cmeo:animation="';
            $aniString .= $this->serializeAnimations();
            $aniString .= '"';
            $svg .= $aniString;
        }

        $svg .= "\r\n" . ' xlink:href="' . $this->getImageUrl() . '"';
        $svg .= "\r\n" . ' linkurl="' . $this->getLinkUrl() . '"';

        if(isset($stroke) || isset($shadow) && !empty($shadow))
        {
            $svg .= "\r\n" . ' style="';
            if(isset($stroke) && $this->strokeEnabled())
            {
                $svg .= 'stroke:' . $stroke->getColor()->getHex() . ';stroke-width:' . $stroke->getWidth() . ';';
            }

            if(isset($shadow) && $this->shadowEnabled())
            {
                $svg .= 'shadow:' . $this->getShadow()->getColor()->getHex() . ';shadow-dist:' . $this->getShadow()->getDist() . 'px;';
            }
            $svg .= '"';
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
     */
    public function setImageUrl($imageUrl)
    {
        $imageUrl = preg_replace('/^\/+/', '/', $imageUrl);
        $imageUrl = str_replace(' ', '%20', $imageUrl);

        $this->imageUrl = $imageUrl;
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
