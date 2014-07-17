<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 11:33
 */

class GfxText extends GfXComponent
{
    private $oColor;
    private $sFontWeight;
    private $iFontVariant;
    private $sFontStyle;
    private $sFontStretch;
    private $sFontSizeAdjust;
    private $iFontSize;
    private $sFontFamily;

    public function __construct(GfxColor $oColor)
    {
        $this->oColor = $oColor;
    }

    public function create()
    {
        //TODO to be defined
    }

    public function setFillColor($sFillColor)
    {
        $this->oColor->setColor($sFillColor);
        //TODO depends on the input (color picker etc.)
        return $this->oColor->getColorHex();
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
        $aAllowedValues = array("sans", "serif");

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
        return $this->iFontSize;
    }

    /**
     * @param $iFontSize
     * @throws InvalidArgumentException
     */
    public function setFontSize($iFontSize)
    {
//        $aAllowedValues = array("larger", "smaller", "xx-small", "x-small", "small", "medium", "large", "x-large", "xx-large", "inherit");
//        if(in_array($sFontSize, $aAllowedValues, true))
//        {
//            $this->iFontVariant = $sFontSize;
//        }

        if(is_numeric($iFontSize))
        {
            $this->iFontSize = $iFontSize;
        }
        else
        {
            $this->throwException($iFontSize);
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
     * @param mixed $iFontSizeAdjust
     */
    public function setFontSizeAdjust($iFontSizeAdjust)
    {
        if(is_numeric($iFontSizeAdjust) || $iFontSizeAdjust === null)
        {
            $this->iFontVariant = $iFontSizeAdjust;
        }
        else
        {
            $this->throwException($iFontSizeAdjust);
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
        throw new InvalidArgumentException('Setting font family failed. Invalid parameter ('.$sParam.') given.');
    }
} 