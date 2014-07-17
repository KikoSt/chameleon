<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 09:28
 */

class GfxColor
{
    private $sColorHex;
    private $oColorRGB;

    public function setColorHex($sColorHex)
    {
        if(preg_match("/^#([0-9a-fA-F]{3}){1,2}$/", $sColorHex))
        {
            $this->sColorHex = $sColorHex;
        }
        else
        {
            throw new InvalidArgumentException("regex");
        }
    }

    public function getColorHex()
    {
        return $this->sColorHex;
    }

    public function setColorRGB($iR, $iG, $iB)
    {
        if(is_numeric($iR) && is_numeric($iG) && is_numeric($iB))
        {
            $oColorRGB = new stdClass();
        }
        else
        {
            throw new InvalidArgumentException();
        }

        $oColorRGB->R = $iR;
        $oColorRGB->G = $iG;
        $oColorRGB->B = $iB;
    }

    public function getColorRGB()
    {
        return $this->oColorRGB;
    }

    public function setR($iR)
    {
        if(is_numeric($iR))
        {
            $this->iR = $iR;
        }
    }

    public function setB($iB)
    {
        if(is_numeric($iB))
        {
            $this->iB = $iB;
        }
    }

    public function setG($iG)
    {
        if(is_numeric($iG))
        {
            $this->iG = $iG;
        }
    }

    private function limitColorHex()
    {

    }
} 