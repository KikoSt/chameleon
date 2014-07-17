<?php
/**
 * Comment here
 *
 * User: thomas.hummel@mediadecision.com
 * Date: 17/07/2014
 * Time: 09:01
 */
class GfXComponent implements Linkable, Resizeable
{

    private $x, $y;
    private $width, $height;
    private $fXPos;
    private $fYPos;
    private $fWidth;
    private $fHeight;
    private $sId;
    private $color;

    public function __construct()
    {
        $this->x = 0;
        $this->y = 0;
    }

    public function create()
    {
        echo 'Now I\'m here ... ' . $this->fXPos . '/' . $this->fYPos . "\n";
    }

    public function setId($sId)
    {
        $this->sId =$sId;
    }

    public function getId()
    {
        return $this->sId;
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
        return $this->height;
    }

    public function render($canvas) {}

    public function createLink($sUrl) {}

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

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        if(is_a($color, 'Color')) {
            $this->color = $color;
        }
    }

}
