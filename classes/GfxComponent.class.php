<?php

class GfXComponent
{

    protected $x, $y;

    public function __construct()
    {
        $this->x = 0;
        $this->y = 0;
    }

    public function render($canvas) {}

    public function setPosition($x, $y)
    {
        $this->setX($x);
        $this->setY($y);
    }

    public function setSize($width, $height)
    {
        $this->setWidth($width);
        $this->setHeight($height);
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function setY($y)
    {
        $this->y = $y;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function setHeight($height)
    {
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
