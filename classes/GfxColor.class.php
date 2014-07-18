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

    public function setHex($sColorHex)
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

    public function getHex()
    {
        return $this->sColorHex;
    }

    public function setRGB($iR, $iG, $iB)
    {
        if(is_numeric($iR) && is_numeric($iG) && is_numeric($iB))
        {
            $oRGB = new stdClass();
            $oRGB->R = $iR;
            $oRGB->G = $iG;
            $oRGB->B = $iB;

            $this->oRGB = $oRGB;
        }
        else
        {
            throw new InvalidArgumentException();
        }


    }

    public function getRGB()
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