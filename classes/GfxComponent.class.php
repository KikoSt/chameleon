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

    public function getPosition()
    {
        $oPosition = new stdClass();
        $oPosition->x = $this->getX();
        $oPosition->y = $this->getY();
        return $oPosition;
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

    public function setColor(GfxColor $oColor)
    {
        $this->color = $oColor;
    }

    public function getCurrentWidth()
    {
        // TODO: Implement getCurrentWidth() method.
    }

    public function getCurrentHeight()
    {
        // TODO: Implement getCurrentHeight() method.
    }

    public function setNewWidth($width)
    {
        // TODO: Implement setNewWidth() method.
    }

    public function setNewHeight($height)
    {
        // TODO: Implement setNewHeight() method.
    }

    public function getNewWidth()
    {
        // TODO: Implement getNewWidth() method.
    }

    public function getNewHeight()
    {
        // TODO: Implement getNewHeight() method.
    }

    public function resize()
    {
        // TODO: Implement resize() method.
    }
}
