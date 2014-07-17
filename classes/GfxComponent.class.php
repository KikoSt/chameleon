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
    private $fXPos;
    private $fYPos;
    private $fWidth;
    private $fHeight;
    private $sId;

    public function __construct()
    {
        $this->fXPos = 0;
        $this->fYPos = 0;
    }

    public function render()
    {
        echo 'Now I\'m here ... ' . $this->fXPos . '/' . $this->fYPos . "\n";
    }

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
