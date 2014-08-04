<?php
/**
 * Comment here
 *
 * User: thomas.hummel@mediadecision.com
 * Date: 17/07/2014
 * Time: 09:01
 */

class GfxRectangle extends GfxShape
{
    public function __construct()
    {
        parent::__construct();
    }

    public function renderSWF($canvas)
    {
        $rect = new SWFShape();

        if($this->getStroke() !== null)
        {
            $strokeWidth = $this->getStroke()->getWidth();
            $stroke = new GfxRectangle();
            $stroke->setWidth($this->getWidth() + ($strokeWidth * 2));
            $stroke->setHeight($this->getHeight() + ($strokeWidth * 2));
            $stroke->setX($this->getX() - $strokeWidth);
            $stroke->setY($this->getY() - $strokeWidth);
            $stroke->setFill($this->getStroke()->getColor());
            $stroke->renderSWF($canvas);

        }

        if($this->getShadowColor() !== null)
        {
            $shadow = new GfxRectangle();
            $shadow->setWidth($this->getWidth());
            $shadow->setHeight($this->getHeight());
            $shadow->setX($this->getX() + (int) $this->getShadowDist());
            $shadow->setY($this->getY() + (int) $this->getShadowDist());
            $shadowColor = $this->getShadowColor();
            $shadowColor->setAlpha(128);
            $shadow->setFill($shadowColor);
            $canvas = $shadow->renderSWF($canvas);
        }

        $r = $this->getFill()->getR();
        $g = $this->getFill()->getG();
        $b = $this->getFill()->getB();
        $a = $this->getFill()->getAlpha();

        $fill = $rect->addFill($r, $g, $b, $a);
        $rect->setRightFill($fill);

        $x1 = $this->getX();
        $y1 = $this->getY();
        $x2 = $this->getX() + $this->getWidth();
        $y2 = $this->getY() + $this->getHeight();

        $rect->movePenTo($x1, $y1);
        $rect->drawLineTo($x1, $y2);
        $rect->drawLineTo($x2, $y2);
        $rect->drawLineTo($x2, $y1);
        $rect->drawLineTo($x1, $y1);

        $handle = $canvas->add($rect);
        $handle->moveTo(0, 0);

        return $canvas;
    }

    public function renderGIF($canvas)
    {
        $x2 = $this->getX() + $this->getWidth();
        $y2 = $this->getY() + $this->getHeight();

        if($this->getFill()->getR() !== null)
        {
            $textColour = imagecolorallocatealpha($canvas, $this->getFill()->getR(), $this->getFill()->getG(), $this->getFill()->getB(), 0);
            imagefilledrectangle($canvas, $this->getX(), $this->getY(), $x2, $y2, $textColour);
        }
        else
        {
            $textColour = imagecolorallocatealpha($canvas, $this->getStroke()->getR(), $this->getStroke()->getB(),
                $this->getStroke()->getG(),
                0);
            imagerectangle($canvas, $this->getX(), $this->getY(), $x2, $y2, $textColour);
        }
        return $canvas;
    }

    public function getSvg()
    {
        $notAllowedParams = array('linkUrl');
        $methods = get_class_methods($this);

        foreach($methods as $method)
        {
            if(false !== strpos($method, 'set'))
            {
                $method = str_replace('set', '', $method);

                if(!in_array($method, $notAllowedParams))
                {
                    $method = preg_replace("/(?<!^)([A-Z])/", "-\\1", $method);
                }
                $method = strtolower($method);

var_dump($method);
            }
        }
    }

    private function determineComponentType()
    {
        switch(get_class($this))
        {

        }
    }
}
