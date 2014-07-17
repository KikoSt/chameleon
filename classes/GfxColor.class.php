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
    private $iR;
    private $iG;
    private $iB;

    public function setColorHex($sColorHex)
    {
        $this->sColorHex = $sColorHex;
    }

    public function getColorHex()
    {
        if(!preg_match("^#(?:[0-9a-fA-F]{3}){1,2}$", $this->sColorHex));
        {
            return "You're doing it wrong";
        }
        return $this->sColorHex;
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