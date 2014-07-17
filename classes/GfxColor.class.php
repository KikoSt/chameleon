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
        if(preg_match("/^#([0-9a-fA-F]{3}){1,2}$/", $sColorHex))  {
            $this->sColorHex = $sColorHex;
        } else {
            echo 'Wrong!' . "\n";
        }
    }

    public function getColorHex()
    {
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