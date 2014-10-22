<?php

/**
 *
 * class GfxGroup
 *
 * Used for grouping GfxComponents in order to allow easier editing and/or animation
 *
 * @author: christoph starkmann <christoph.starkmann@mediadecision.com>
 */

 class GfxGroup
 {
    private $id;
    private $x, $y;
    private $maxX, $maxY;
    private $width, $height;
    private $fontFamily;
    private $fontSize;
    private $link;
    private $foregroundColor;
    private $backgroundColor;
    private $text;

    private $container;
    private $elements;

    private $cmeoRef;

    public function __construct($id, GfxContainer $container)
    {
        $this->id = $id;
        $this->container = $container;
        $this->x = $this->container->getCanvasWidth();
        $this->y = $this->container->getCanvasHeight();
        $this->maxX   = 0;
        $this->maxY   = 0;
        $this->width  = 0;
        $this->height = 0;
    }

    public function create()
    {
        foreach($this->container->getElements() AS $element)
        {
            // check all elements of the container for elements with matching groupId,
            if($element->getEditGroup() === $this->getId())
            {
                $elemX      = (int)$element->getX();
                $elemY      = (int)$element->getY();
                $elemWidth  = (int)$element->getWidth();
                $elemHeight = (int)$element->getHeight();

                if($element instanceof GfxText) {
                    $elemY -= $elemHeight;
                }

                // TODO: adjust for text :(
                if($elemX < (int)$this->getX())
                {
                    $this->setX($elemX);
                }
                if($elemY < $this->getY())
                {
                    $this->setY($elemY);
                }

                if($elemX + $elemWidth > (int)$this->maxX)
                {
                    $this->maxX = (int)$elemX + (int)$elemWidth;
                }
                if($elemY + $elemHeight > (int)$this->maxY)
                {
                    $this->maxY = (int)$elemY + (int)$elemHeight;
                }

                // foreground and background color: Rectangle defines bg, text defines fg
                if((method_exists($element, 'getFill') && $element->getFill()) && $element instanceof GfxText)
                {
                    $this->foregroundColor = $element->getFill();
                }
                if((method_exists($element, 'getFill') && $element->getFill()) && $element instanceof GfxRectangle)
                {
                    $this->backgroundColor = $element->getFill();
                }

                // get font family
                if((method_exists($element, 'getFontFamily') && $element->getFontFamily()) && $element instanceof GfxText)
                {
                    $this->fontFamily = $element->getFontFamily();
                }

                // calculate own properties based on those elements' properties
                // $this->elements[] = $element;
            }
        }

        $this->width  = $this->maxX - $this->x;
        $this->height = $this->maxY - $this->y;

    }



    public function getX()
    {
        return $this->x;
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setY($y)
    {
        $this->y = $y;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getForegroundColor()
    {
        return $this->foregroundColor;
    }

    public function setForegroundColor(GfxColor $foregroundColor)
    {
        $this->foregroundColor = $foregroundColor;
    }

    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(GfxColor $backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }


    public function getElements()
    {
        return $this->elements;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // cmeoRef
    public function setSource($source)
    {
        $this->Source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    // cmeoRel
    public function setLink($link)
    {
        $this->link = $source;
    }

    public function getLink()
    {
        return $this->link;
    }


    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getFontFamily()
    {
        return $this->fontFamily;
    }

    public function setFontFamily($fontFamily)
    {
        $this->fontFamily = $fontFamily;
    }
}
