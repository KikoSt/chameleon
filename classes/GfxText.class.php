<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 11:33
 */

class GfxText extends GfxComponent
{
    private $text;
    private $color;
    private $oFont;
    private $sFontWeight;
    private $iFontVariant;
    private $sFontStyle;
    private $sFontStretch;
    private $sFontSizeAdjust;
    private $fontSize;
    private $sFontFamily;

    public function __construct()
    {
        parent::__construct();
        $this->oFont = new SWFFont('fdb/Bitstream Vera Serif.fdb');
    }

    public function create($svgRootNode)
    {
        parent::create($svgRootNode);

        $this->setText((string) $svgRootNode);

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
    }


    public function getTextWidth() {
        $text = new SWFText();
        $text->setFont($this->getFont());
        $text->setHeight($this->getFontSize());
        $width = $text->getWidth($this->getText());
        unset($text);
        return($width);
    }


    public function renderSWF($canvas)
    {
        $text = new SWFText();
        if(null !== $this->getFont()) {
            $text->setFont($this->getFont());
        } else {
            die('No font set!');
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
        $text->setHeight($this->getFontSize());
        $tWidth = $text->getWidth($this->getText());
        // position: CENTERED!
        $text->moveTo($this->getX() - ($this->getTextWidth()/2), $this->getY());
        $text->addString($this->getText());

        $handle = $canvas->add($text);

        return $canvas;
    }





    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }



    /**
     * @return mixed
     */
    public function getFont()
    {
        return $this->oFont;
    }

    /**
     * @param mixed $oFont
     */
    public function setFont(SWFFont $oFont)
    {
        $this->oFont = $oFont;
    }

    /**
     * @param $sFontWeight
     * @throws InvalidArgumentException
     */
    public function setFontWeight($sFontWeight)
    {
        $aAllowedValues = array("normal", "bold", "bolder", "lighter", "100", "200", "300", "400", "500", "600", "700", "800", "900");

        if(in_array(strtolower($sFontWeight), $aAllowedValues, true))
        {
            $this->sFontWeight = $sFontWeight;
        }
        else
        {
            $this->throwException($sFontWeight);
        }
    }

    /**
     * @return mixed
     */
    public function getFontWeight()
    {
        return $this->sFontWeight;
    }

    /**
     * @param $sFontVariant
     * @throws InvalidArgumentException
     */
    public function setFontVariant($sFontVariant)
    {
        $aAllowedValues = array("normal", "small-caps");

        if(in_array(strtolower($sFontVariant), $aAllowedValues, true))
        {
            $this->iFontVariant = $sFontVariant;
        }
        else
        {
            $this->throwException($sFontVariant);
        }
    }

    /**
     * @return mixed
     */
    public function getFontVariant()
    {
        return $this->iFontVariant;
    }


    /**
     * @return mixed
     */
    public function getFontStyle()
    {
        return $this->sFontStyle;
    }

    /**
     * @param $sFontStyle
     * @throws InvalidArgumentException
     */
    public function setFontStyle($sFontStyle)
    {
        $aAllowedValues = array("normal", "italic", "oblique");

        if(in_array(strtolower($sFontStyle), $aAllowedValues, true))
        {
            $this->iFontVariant = $sFontStyle;
        }
        else
        {
            $this->throwException($sFontStyle);
        }
    }


    /**
     * @return mixed
     */
    public function getFontFamily()
    {
        return $this->sFontFamily;
    }

    /**
     * @param $sFontFamily
     * @throws InvalidArgumentException
     */
    public function setFontFamily($sFontFamily)
    {
        $aAllowedValues = array("sans", "serif", "sans-serif");

        if(in_array(strtolower($sFontFamily), $aAllowedValues, true))
        {
            $this->iFontVariant = $sFontFamily;
        }
        else
        {
            $this->throwException($sFontFamily);
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
//            $this->iFontVariant = $sFontSize;
//        }

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
     * @return mixed
     */
    public function getFontSizeAdjust()
    {
        return $this->sFontSizeAdjust;
    }

    /**
     * @param mixed $fontSizeAdjust
     */
    public function setFontSizeAdjust($fontSizeAdjust)
    {
        if(is_numeric($fontSizeAdjust) || $fontSizeAdjust === null)
        {
            $this->iFontVariant = $fontSizeAdjust;
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
        return $this->sFontStretch;
    }

    /**
     * @param mixed $sFontStretch
     */
    public function setFontStretch($sFontStretch)
    {
        $aAllowedValues = array("normal", "wider", "narrower", "ultra-condensed", "extra-condensed", "condensed", "semi-condensed", "semi-expanded", "expanded", "extra-expanded", "ultra-expanded");

        if(in_array(strtolower($sFontStretch), $aAllowedValues, true))
        {
            $this->iFontVariant = $sFontStretch;
        }
        else
        {
            $this->throwException($sFontStretch);
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

    public function getFill()
    {
        return $this->fill;
    }

    public function setFill(GfxColor $fill)
    {
        $this->fill = $fill;
    }
}
