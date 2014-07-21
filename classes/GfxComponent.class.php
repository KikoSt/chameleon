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
    private $id;
    private $color;
    private $stroke;
    private $url;

    public function __construct()
    {
        $this->x      = 0;
        $this->y      = 0;
        $this->width  = 0;
        $this->height = 0;
    }

    public function create()
    {
        echo 'Now I\'m here ... ' . $this->x . '/' . $this->y . "\n";
    }

    public function getStroke()
    {
        return $this->stroke;
    }

    public function setStroke(GfxColor $oColor)
    {
        $this->stroke = $oColor;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
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

    public function setLink($url)
    {
        $this->url = $url;
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

    public function getFill()
    {
        return $this->color;
    }

    public function setFill(GfxColor $oColor)
    {
        $this->color = $oColor;
    }


    /* *************************
            Magic Methods
    ************************* */
    public function __toString()
    {
        $string = '';
        $string .= get_class($this);
        return $string;
    }
}
