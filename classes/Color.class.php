<?php
class Color
{
    private $r, $g, $b;

    public function __construct($rOrHex, $g=null, $b=null)
    {
        if($g != null && $b != null) {
            $this->r = $this->limitColor($r);
            $this->g = $this->limitColor($g);
            $this->b = $this->limitColor($b);
        } else {
            if(substr($rOrHex, 0, 1) !== '#') {
                return false;
            } else {
                $color = $this->limitColor($rOrHex);
            }
        }
    }

    private function limitColor($value)
    {
        if(substr($value, 0, 1) == '#') {
            // two-digit hex values cannot be larger than 255, so
            // we're save here; NO short notation (#fff = #ffffff)
            // allowed for now
            // shortening the hex value, though, but we may later accept
            // a fourth pair as alpha value
            $value = substr($value, 0, 7);
        } else {
            if($value > 255) $value = 255;
            if($value < 0) $value = 0;
        }
        return $value;
    }

    public function setR($r)
    {
        $this->r = $this->limitColor($r);
    }

    public function setG($g)
    {
        $this->g = $this->limitColor($g);
    }

    public function setB($b)
    {
        $this->b = $this->limitColor($b);
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
}
?>
