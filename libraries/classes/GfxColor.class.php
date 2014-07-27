<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 09:28
 */

class GfxColor
{
    private $r, $g, $b;

    public function __construct($rOrHex=null, $g=null, $b=null)
    {
        if(preg_match("/^#([0-9a-fA-F]{3}){1,2}$/", $rOrHex))
        {
            $hex = $rOrHex;
            $this->setHex($hex);
            echo $hex;
            // hex value passed
        }
        else if((int) $rOrHex == $rOrHex)
        {
            $r = (int) $rOrHex;
            echo 'initializing with r=' . $r . "\n";
            $this->setR($r);
            $this->setG($g);
            $this->setB($b);
        }
    }

    public function setHex($colorHex)
    {
        if(preg_match("/^#([0-9a-fA-F]{3}){1,2}$/", $colorHex))
        {
            $this->setR(hexdec(substr($colorHex, 1, 2)));
            $this->setG(hexdec(substr($colorHex, 3, 2)));
            $this->setB(hexdec(substr($colorHex, 5, 2)));
        }
        else
        {
            throw new InvalidArgumentException("regex");
        }
    }

    public function getHex()
    {
        return '#' . (sprintf('%02x', $this->getR())) . sprintf('%02x', $this->getG()) . sprintf('%02x', $this->getB());
    }

    public function setRGB($r, $g, $b)
    {
        // check ALL values before changing any to prevent unexpected colors ... ;)
        if(is_numeric($r) && is_numeric($g) && is_numeric($b))
        {
            $this->setR($r);
            $this->setG($g);
            $this->setB($b);

        }
        else
        {
            throw new InvalidArgumentException();
        }
    }

    public function getR()
    {
        return $this->r;
    }

    public function getG()
    {
        return $this->g;
    }

    public function getB()
    {
        return $this->b;
    }

    public function setR($r)
    {
        $this->r = $this->checkColorValue($r);
    }

    public function setG($g)
    {
        $this->g = $this->checkColorValue($g);
    }

    public function setB($b)
    {
        $this->b = $this->checkColorValue($b);
    }

    private function checkColorValue($value)
    {
        if(!is_numeric($value)) {
            return false;
        }
        if($value < 0) $value = 0;
        if($value > 255) $value = 255;

        return $value;
    }
    private function limitColorHex()
    {

    }
}
