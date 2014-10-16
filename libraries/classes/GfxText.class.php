<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 11:33
 */
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

    public function __construct(GfxContainer $container)
    {
        parent::__construct($container);
    }

    public function create($svgRootNode)
    {
        parent::create($svgRootNode);

        //$this->setText(utf8_decode((string) $svgRootNode));
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
//                 echo 'Product information found; ';
//                 echo '[' . $this->getRef() . ']';
//                 echo "\n";
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

//             if(!empty($this->getLink()))
//             {
//                 echo "\n[" . $this->getLink() . "]\n";
//             }
        }
    }

    public function renderSWF($canvas)
    {
        $text = new SWFText();

        if($this->hasShadow())
        {
            $shadow = new GfxText($this->getContainer());
            $shadow->setWidth($this->getWidth());
            $shadow->setHeight($this->getHeight());
            $shadow->setX($this->getX() + (int) $this->getShadow()->getDist());
            $shadow->setY($this->getY() + (int) $this->getShadow()->getDist());

            if(null !== $this->getSWFFont()) {
                $shadow->setFontFamily($this->getFontFamily());
            } else {
                throw new Exception('No font set!');
            }

            $shadowColor = $this->getShadow()->getColor();
            $shadowColor->setAlpha(128); // currently not working, most likely due to the text type!
            $shadow->setFill($shadowColor);
            $shadow->setFontSize($this->getFontSize());
            $shadow->setText($this->getText());
            $canvas = $shadow->renderSWF($canvas);
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
        $text->moveTo($this->getX() - ($this->getTextWidth()/2), $this->getY());
        $text->moveTo($this->getX(), $this->getY());
        // $text->addString(utf8_decode(str_replace('€', ' Euro', $this->getText())));
        $text->addString(utf8_decode(str_replace('€', ' Euro', $this->getText())));

        $handle = $canvas->add($text);
        unset($handle);

        return $canvas;
    }

    public function renderGif($canvas)
    {
        $textColor = imagecolorallocate($canvas,$this->getFill()->getR(),$this->getFill()->getG(),$this->getFill()->getB());

        if($this->hasShadow())
        {
            $this->renderShadow($canvas);
        }

        imagettftext($canvas,
                     $this->getFontSize(),
                     0,
                     $this->getX(),
                     $this->getY(),
                     $textColor,
                     $this->getGIFFont(),
//                     str_replace('€', ' Euro', $this->getText())
                    $this->getText()  //TODO € works actually, we have to keep an eye on it
        );


        return $canvas;
    }

    public function renderShadow($canvas)
    {
        $color = imagecolorallocatealpha($canvas,
                                         $this->getShadow()->getColor()->getR(),
                                         $this->getShadow()->getColor()->getG(),
                                         $this->getShadow()->getColor()->getB(),
                                         50
                 );

        imagettftext($canvas,
            $this->getFontSize(),
            0,
            $this->getX() + $this->getShadow()->getDist(),
            $this->getY() + $this->getShadow()->getDist(),
            $color,
            $this->getGIFFont(),
            utf8_decode(str_replace('€', ' Euro', $this->getText()))
        );
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
