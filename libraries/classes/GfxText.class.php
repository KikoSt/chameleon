<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 11:33
 */
use gdenhancer\GDEnhancer;

require_once(ROOT_DIR . 'config/fontconfig.inc.php');
define('FLASH_FONT_SCALE_FACTOR', 1.32);

class GfxText extends GfxComponent
{
    private $text;
    private $fontWeight;
    private $fontVariant;
    private $fontStyle;
    private $fontStretch;
    private $fontSizeAdjust;
    private $fontSize;
    private $fontFamily;
    private $textAnchor;

    private $rotation;

    public function __construct(GfxContainer $container)
    {
        parent::__construct($container);
    }

    public function create($svgRootNode)
    {
        parent::create($svgRootNode);

        $this->setText(((string) $svgRootNode));

        $attr = $svgRootNode->attributes();

        $fill = new GfxColor();
        $fill->setHex((string) $attr->fill);
        $this->setFill($fill);

        $this->setFontSize((float) $attr->{"font-size"});

        if(null !== ((string) $attr->{"font-weight"}) && !empty((string) $attr->{"font-weight"})) {
            $fontWeight = (string) $attr->{"font-weight"};
        } else {
            $fontWeight = 'normal';
        }
        $this->setFontWeight($fontWeight);

        if(null !== ((string) $attr->{"font-variant"}) && !empty((string) $attr->{"font-variant"})) {
            $fontVariant = (string) $attr->{"font-variant"};
        } else {
            $fontVariant = 'normal';
        }
        $this->setFontVariant($fontVariant);
        $this->setFontFamily((string) $attr->{'font-family'});
    }


    public function getTextWidth()
    {
        $text = new SWFText();
        $text->setFont($this->getSWFFont());
        $text->setHeight($this->getFontSize() * FLASH_FONT_SCALE_FACTOR);
        $width = $text->getWidth($this->getText());
        unset($text);
        return($width);
    }

    public function getWidth()
    {
        return $this->getTextWidth();
    }

    public function getTextHeight()
    {
        return $this->getFontSize();
    }

    public function getHeight()
    {
        return $this->getTextHeight();
    }

    public function updateData()
    {
        parent::updateData();

        if($this->getContainer()->getProductData())
        {
            if(!empty($this->getRef()))
            {
                $productData = $this->getContainer()->getProductData();

                $newValue = $productData->{'get' . $this->getRef()}();
                if('price' === $this->getRef() || 'oldPrice' === $this->getRef())
                {
                    $newValue = number_format($newValue, 2, ',', '');

                    if(empty($productData->getCurrencySymbol()) && empty($productData->getCurrencyShort()))
                    {
                        $newValue .= '€';
                    }
                    else
                    {
                        if(!empty($productData->getCurrencySymbol()))
                        {
                            echo '1';
                            $newValue .= $productData->getCurrencySymbol();
                        }
                        else
                        {
                            echo '2';
                            $newValue .= $productData->getCurrencyShort();
                        }
                    }
                }
                $this->setText($newValue);
            }
        }
    }

    public function renderSWF($canvas)
    {
        $text = new SWFText();
        $sprite = new SWFSprite();
        $sprite->setFrames($this->getContainer()->getFramerate());

        if($this->hasShadow())
        {
            $shadow = new SWFText();

            if(null !== $this->getSWFFont()) {
                try
                {
                    $shadow->setFont($this->getSWFFont());
                }
                catch(Exception $e)
                {
                    echo 'Error trying to open font ' . $this->getSWFFont();
                }
            } else {
                throw new Exception('No font set!');
            }
            try {
                $shadowFill = $this->getShadow()->getColor();
            } catch(Exception $e) {
                echo 'Error trying to get color';
                return false;
            }
            $shadowDist = $this->getShadow()->getDist();
            try {
                $shadow->setColor($shadowFill->getR(), $shadowFill->getG(), $shadowFill->getB(), 128);
            } catch(Exception $e) {
                echo 'Error trying to set color!';
                return false;
            }
            $shadow->setHeight($this->getFontSize() * FLASH_FONT_SCALE_FACTOR);
            // position: CENTERED!

            // $shadow->moveTo(- ($this->getTextWidth()/2), 0);
            $shadow->moveTo(-$this->getTextWidth() / 2 + $shadowDist, $shadowDist);
            $shadow->addString(utf8_decode(str_replace('€', ' Euro', $this->getText())));
            $shandle = $sprite->add($shadow);
        }

        if(null !== $this->getSWFFont()) {
            try
            {
                $text->setFont($this->getSWFFont());
            }
            catch(Exception $e)
            {
                echo 'Error trying to open font ' . $this->getSWFFont();
            }
        } else {
            throw new Exception('No font set!');
        }
        try {
            $curFill = $this->getFill();
        } catch(Exception $e) {
            echo 'Error trying to get color';
            return false;
        }
        try {
            $text->setColor($curFill->getR(), $curFill->getG(), $curFill->getB());
        } catch(Exception $e) {
            echo 'Error trying to set color!';
            return false;
        }
        $text->setHeight($this->getFontSize() * FLASH_FONT_SCALE_FACTOR);
        // position: CENTERED!

        $text->moveTo(- ($this->getTextWidth()/2), 0);
        $text->addString(utf8_decode(str_replace('€', ' Euro', $this->getText())));

        $handle = $sprite->add($text);

        if($this->drawCenter)
        {
            $chandle = $this->drawCenter($sprite);
        }

        $lhandle = $this->addClickableLink($sprite);

        /**
         *  Prepare actual animation
        **/
        if(count($this->getAnimations()) > 0)
        {
            $handleList = array();
            if(isset($chandle) && false !== $chandle)
            {
                $handleList['centerHandle'] = $chandle;
            }
            if(isset($lhandle) && false !== $lhandle)
            {
                $handleList['linkHandle'] = $lhandle;
            }
            if(isset($shandle))
            {
                $handleList['shadowHandle'] = $shandle;
            }
            $handleList['handle'] = $handle;
            $sprite = $this->swfAnimate($handleList, $sprite);
        }
        /**
         *  Animation done!
        **/

        $handle = $canvas->add($sprite);
        $handle->moveTo($this->getX() + ($this->getTextWidth()/2), $this->getY());
        $sprite->nextFrame();

        unset($handle);

        return $canvas;
    }

    public function renderGif($transformationList = null, $skip = false)
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

        $transparent = new ImagickPixel("rgba(127,127,127,0)");

        //set the color for the layer
        $text = new ImagickDraw();
        $text->setFont($this->getGIFFont());
        // WHY?????????????????????????
        $text->setFontsize($this->getFontSize() * 1.33);
        $text->setFillColor($this->getFill()->getHex());

        $imageWidth  = $this->getContainer()->getCanvasWidth();
        $imageHeight = $this->getContainer()->getCanvasHeight();

        //create a new layer
        $image = new Imagick();
        $image->newImage($imageWidth, $imageHeight, $transparent);
        // IMPORTANT! Clean up animation mess!
         $image->setImageDispose(3);

        $x = $this->gifParams->x;
        $y = $this->gifParams->y;
        $width = $this->gifParams->width;
        $height = $this->gifParams->height;
        $rotation = $this->gifParams->rotation;

        if($this->hasShadow() && $this->shadowEnabled())
        {
            $text->setFillColor(new ImagickPixel($this->getShadow()->getColor()->getHex() . 'ff'));
            $text->setStrokeAntialias(true);
            $image->annotateImage($text, $this->getShadow()->getDist(), $height + 10 + $this->getShadow()->getDist(), 0, $this->getText());
        }
        //add the text
        $text->setFillColor($this->getFill()->getHex());
        $image->annotateImage($text, 0, $height + 10, 0, $this->getText());

        if($this->getWidth() == 0)
        {
            throw new Exception('zero width for text ' . $this->getText() . ', element ' . $this->getId());
        }
        else
        {
            $xScale = $width / $this->getWidth();
            $yScale = $height / $this->getHeight();
        }

        $distort = array($width/2, $height/2, $xScale, $yScale,  -$rotation, $x + $width / 2, $y - $height * 1.3);
        $image->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        $image->distortImage(imagick::DISTORTION_SCALEROTATETRANSLATE, $distort, false);

        return $image;

    }

    public function renderShadow($frame)
    {
        $text = new ImagickDraw();
        $text->setFont($this->getGIFFont());
        $text->setfontsize($this->getFontSize() * 1.33);
        $text->setfillcolor(new ImagickPixel($this->getShadow()->getColor()->getHex()));

//        $frame->annotateImage($text, $this->getX()+, $this->getY()+$this->getShadow()->getDist(), 0, $this->getText());
    }

    public function getFontListForOverview()
    {
        $fontlist = $GLOBALS['fontlist']['GIF'];

        $cleansedFontList = array();

        foreach($fontlist as $key => $font)
        {
            $fontFile = str_replace(FONT_TTF_DIR, '', $font);
            $fontFile = trim($fontFile, '/');
            $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fontFile);
            $cleansedFontList[$key] = $withoutExt;
        }
        return $cleansedFontList;
    }

    public function getSvg()
    {
        $stroke = $this->getStroke();
        $shadow = $this->getShadow();

        $svg = '';
        $svg .= "\r\n" . '<text xml:space="preserve"';
        $svg .= "\r\n" . ' cmeo:ref="' . $this->getCmeoRef(). '"';
        $svg .= "\r\n" . ' cmeo:link="' . $this->getCmeoLink(). '"';
        $svg .= "\r\n" . ' cmeo:editGroup="' . $this->getEditGroup(). '"';
        if(count($this->getAnimations()) > 0)
        {
            $aniString  = ' cmeo:animation="';
            $aniString .= $this->serializeAnimations();
            $aniString .= '"';
            $svg .= $aniString;
        }

        $svg .= "\r\n" . ' text-anchor="' . $this->getTextAnchor() . '"';
        $svg .= "\r\n" . ' font-family="' . $this->getFontFamily() . '"';
        $svg .= "\r\n" . ' font-size="' . $this->getFontSize() . '"';
        $svg .= "\r\n" . ' fill="' . $this->getFill()->getHex() . '"';

        if(isset($stroke))
        {
            $svg .= "\r\n" . ' stroke="' . $stroke->getColor()->getHex() . '"';
            $svg .= "\r\n" . ' stroke-width="' . $stroke->getWidth() . '"';
        }

        if(isset($shadow) && $this->shadowEnabled())
        {
            $svg .= "\r\n" . ' style="shadow:' . $shadow->getColor()->getHex() . ';shadow-dist:' . $shadow->getDist() . 'px;"';
        }

        $svg .= "\r\n" . ' x="' . $this->getX() . '"';
        $svg .= "\r\n" . ' y="' . $this->getY() . '"';
        $svg .= "\r\n" . ' width="' . $this->getWidth() . '"';
        $svg .= "\r\n" . ' height="' . $this->getHeight() . '"';
        $svg .= "\r\n" . ' id="' . $this->getId() . '"';
        $svg .= "\r\n" . '><![CDATA[' . $this->getText() . ']]></text>';
        return $svg;
    }


    public function getTextAnchor()
    {
        return $this->textAnchor;
    }

    public function setTextAnchor($textAnchor)
    {
        $this->textAnchor = $textAnchor;
    }




    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $text = str_replace('â‚¬', '€', $text);
        $text = str_replace('Ã¤', 'ä', $text);
        $text = str_replace('Ã¼', 'ü', $text);
        $this->text = $text;
    }



    public function getSWFFont()
    {
        $font = new SWFFont($GLOBALS['fontlist']['SWF'][$this->getFontFamily()]);
        return $font;
    }

    public function getGIFFont()
    {
        return $GLOBALS['fontlist']['GIF'][$this->getFontFamily()];
    }

    public function setFontWeight($fontWeight)
    {
        $allowedValues = array('normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900');

        if(in_array(strtolower($fontWeight), $allowedValues, true)) {
            $this->fontWeight = $fontWeight;
        } else {
            $this->throwException($fontWeight);
        }
    }

    public function getFontWeight()
    {
        return $this->fontWeight;
    }

    public function setFontVariant($fontVariant)
    {
        $allowedValues = array("normal", "small-caps");

        if(in_array(strtolower($fontVariant), $allowedValues, true)) {
            $this->fontVariant = $fontVariant;
        } else {
            $this->throwException($fontVariant);
        }
    }

    public function getFontVariant()
    {
        return $this->fontVariant;
    }


    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * @param $fontStyle
     * @throws InvalidArgumentException
     */
    public function setFontStyle($fontStyle)
    {
        $allowedValues = array("normal", "italic", "oblique");

        if(in_array(strtolower($fontStyle), $allowedValues, true)) {
            $this->fontStyle = $fontStyle;
        } else {
            $this->throwException($fontStyle);
        }
    }


    /**
     * @return mixed
     */
    public function getFontFamily()
    {
        return $this->fontFamily;
    }

    /**
     * @param $fontFamily
     * @throws InvalidArgumentException
     */
    public function setFontFamily($fontFamily)
    {
        // check if font file exists
        if(array_key_exists($fontFamily, $GLOBALS['fontlist']['SWF']) || array_key_exists($fontFamily, $GLOBALS['fontlist']['GIF'])) {
            $this->fontFamily = $fontFamily;
        } else {
            $this->throwException($fontFamily);
        }
    }

    /**
     * @return mixed
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param $fontSize
     * @throws InvalidArgumentException
     */
    public function setFontSize($fontSize)
    {
//        $aAllowedValues = array("larger", "smaller", "xx-small", "x-small", "small", "medium", "large", "x-large", "xx-large", "inherit");
//        if(in_array($sFontSize, $aAllowedValues, true))
//        {
//            $this->fontVariant = $sFontSize;
//        }
        // TODO: check if $fontSize is a string, if yes, compare with whitelist, if not it must be numeric!
        if(!empty($fontSize))
        {
            $this->fontSize = $fontSize;
        }
        else
        {
            $this->throwException($fontSize);
        }
    }

    /**
     * getFontSizeAdjust
     *
     * @access public
     * @return int
     */
    public function getFontSizeAdjust()
    {
        return $this->fontSizeAdjust;
    }

    /**
     * @param mixed $fontSizeAdjust
     */
    public function setFontSizeAdjust($fontSizeAdjust)
    {
        if(is_numeric($fontSizeAdjust) || $fontSizeAdjust === null)
        {
            $this->fontSizeAdjust = $fontSizeAdjust;
        }
        else
        {
            $this->throwException($fontSizeAdjust);
        }
    }

    /**
     * @return mixed
     */
    public function getFontStretch()
    {
        return $this->fontStretch;
    }

    /**
     * @param mixed $fontStretch
     */
    public function setFontStretch($fontStretch)
    {
        $aAllowedValues = array("normal", "wider", "narrower", "ultra-condensed", "extra-condensed", "condensed", "semi-condensed", "semi-expanded", "expanded", "extra-expanded", "ultra-expanded");

        if(in_array(strtolower($fontStretch), $aAllowedValues, true))
        {
            $this->fontStretch = $fontStretch;
        }
        else
        {
            $this->throwException($fontStretch);
        }
    }

    /**
     * @param $sParam
     * @throws InvalidArgumentException
     */
    private function throwException($sParam)
    {
        throw new InvalidArgumentException('Invalid parameter ('.$sParam.') given.');
    }
}
