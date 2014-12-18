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

require_once(__ROOT__ . 'libraries/functions.inc.php');

class GfxImage extends GfXComponent
{
    private $imageUrl;
    private $tempPath;

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


    public function updateTempImage()
    {
        $this->setTempPath($this->createResizedImage());
    }

    public function updateData()
    {
        parent::updateData();

        if($this->getContainer()->getProductData())
        {
            if(!empty($this->getRef()))
            {
                $this->setImageUrl($this->getContainer()->getProductData()->getImageUrl());
                $this->setTempPath($this->createResizedImage());
            }

            if(!empty($this->getCmeoLink()))
            {
                $this->setCmeoLink($this->getContainer()->getProductData()->getProductUrl());
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
        if((string) $svgRootNode->attributes()->linkurl !== '')
        {
            $this->setLinkUrl((string) $svgRootNode->attributes()->linkurl);
        }

        $imageUrl = (string) $svgRootNode->attributes('xlink', true)->href;
        $imageUrl = str_replace("//assets", "/assets", $imageUrl);
        $this->setImageUrl($imageUrl);

        $this->setTempPath($this->createResizedImage());
    }

    /**
     * createResizedImage

     * create a resized version of the image IF REQUIRED, storing the file temporarily
     * and returning the path
     *
     * NOTE: This method should only be executed ONCE, not over and over again for each
     * frame;
     *
     * @access public
     * @return void
     */
    public function createResizedImage($mode = 'fill')
    {
        // 3 possibilities:
        // - crop:   insert a larger pic as is, i.e. crop the bottom and/or right borders
        // - center: resize and center image keeping the spect ratio
        // - fill:   resize to element size ignoring the aspect ratio
        $modes = array('crop', 'center', 'fill');
        if(!in_array($mode, $modes))
        {
            return false;
        }
        $imagePath = str_replace('http:/', 'http://', $this->getImagePath());
        $image = new Imagick($imagePath);
        $dimensions  = $image->getImageGeometry();
        $imgWidth    = $dimensions['width'];
        $imgHeight   = $dimensions['height'];

        if($mode === 'center')
        {
            $aspectRatio = $imgWidth / $imgHeight;

            $newWidth  = $this->getWidth();
            $newHeight = $this->getHeight();

            if($aspectRatio > 1)
            {
                $newWidth = $newHeight * $aspectRatio;
            }
            else
            {
                $newHeight = $newWidth / $aspectRatio;
            }

            $image->scaleImage($newWidth, $newHeight, true);

            $newX = ($this->getWidth() - $newWidth) / 2;
            $newY = ($this->getHeight() - $newHeight) / 2;
        }
        else if($mode === 'crop')
        {
            $newX = 0;
            $newY = 0;
        }
        else if($mode === 'fill')
        {
            $newWidth  = $this->getWidth();
            $newHeight = $this->getHeight();

            $image->scaleImage($newWidth, $newHeight, false);

            $newX = 0;
            $newY = 0;
        }

        $background = new Imagick();
        $background->newImage($this->getWidth(), $this->getHeight(), new ImagickPixel('white'));
        $background->setImageFormat('gif');

        $background->compositeImage($image, Imagick::COMPOSITE_DEFAULT, $newX, $newY);

        $filename = $this->getId() . '.gif';
        $path = 'tmp';
        imSaveImage($background, $filename, $path);

        return $path . '/' . $filename;
    }




    /**
     * getImagePath
     *
     * evaluation and adjustment of imagePath;
     *
     * TODO: name and or functionality? Restructure all those methods more clearly!
     *
     * @access public
     * @return void
     */
    public function getImagePath()
    {
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
        $filepath = str_replace('//' , '/', $filepath);

        return $filepath;
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

        $output = $this->createImageFromSourceFile($this->getTempPath());

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

        $filepath = $this->getImagePath();
        $filepath = __ROOT__ . $this->getTempPath();

        $transparent = new ImagickPixel("rgba(127,127,127,0)");

        $image = new Imagick($filepath);

        $imageWidth  = $this->getContainer()->getCanvasWidth();
        $imageHeight = $this->getContainer()->getCanvasHeight();

        $frame = new Imagick();
        $frame->newImage($imageWidth, $imageHeight, $transparent);

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

        $frame->compositeImage($image, Imagick::COMPOSITE_DEFAULT, 0, 0);
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
        $width =  $this->getStroke()->getWidth();
        $height = $this->getStroke()->getWidth();
        $image->borderimage($this->getStroke()->getColor()->getHex(), $width, $height);
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
        $svg .= "\r\n" . ' cmeo:ref="' . $this->getRef(). '"';
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

    public function getTempPath()
    {
        return $this->tempPath;
    }

    public function setTempPath($path)
    {
        $this->tempPath = $path;
    }


}
