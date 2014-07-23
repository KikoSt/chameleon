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
    private $fill;
    private $stroke;
    private $linkUrl;

    public function __construct()
    {
        $this->x      = 0;
        $this->y      = 0;
        $this->width  = 0;
        $this->height = 0;
    }

    public function create($svgRootNode)
    {
        $attr = $svgRootNode->attributes();

        $this->setX((float) $attr->x);
        $this->setY((float) $attr->y);
        $this->setWidth((float) $attr->width);
        $this->setHeight((float) $attr->height);

        $this->setId((string) $attr->id);
//        var_dump($this);
    }

    public function getStroke()
    {
        return $this->stroke;
    }

    public function setStroke(GfxColor $oColor)
    {
        $this->stroke = $oColor;
    }

    public function setLinkUrl($linkUrl)
    {
        $this->linkUrl = $linkUrl;
    }

    public function getLinkUrl()
    {
        return $this->linkUrl;
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
        return $this->fill;
    }

    public function setFill(GfxColor $color)
    {
        $this->fill = $color;
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
