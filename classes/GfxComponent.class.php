<?php

class GfXComponent
{

    var $x, $y;

    public function __construct()
    {
        $this->x = 0;
        $this->y = 0;
    }

    public function render() {}

    public function setPosition($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function setSize($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height();
    }
}

?>
