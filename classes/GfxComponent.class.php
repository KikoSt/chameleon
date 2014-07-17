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
<<<<<<< HEAD

    protected $x, $y;
=======
    private $fXPos;
    private $fYPos;
    private $fWidth;
    private $fHeight;
    private $sId;
>>>>>>> 84d3f2cf74596f7ff2912828dcf7ab2e18a696c9

    public function __construct()
    {
        $this->fXPos = 0;
        $this->fYPos = 0;
    }

    public function create()
    {
        echo 'Now I\'m here ... ' . $this->fXPos . '/' . $this->fYPos . "\n";
    }

<<<<<<< HEAD
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
=======
    public function createLink($sUrl) {}

    public function setPosition($x, $y)
    {
        $this->fXPos = $x;
        $this->fYPos = $y;
    }

    public function setSize($fWidth, $fHeight)
    {
        $this->fWidth = $fWidth;
        $this->fHeight = $fHeight;
>>>>>>> 84d3f2cf74596f7ff2912828dcf7ab2e18a696c9
    }

    public function getX()
    {
        return $this->fXPos;
    }

    public function getY()
    {
        return $this->fYPos;
    }

    public function getWidth()
    {
        return $this->fWidth;
    }

    public function getHeight()
    {
        return $this->fHeight();
    }

    public function setId($sId)
    {
        $this->sId =$sId;
    }

    public function getId()
    {
        return $this->sId;
    }
}
