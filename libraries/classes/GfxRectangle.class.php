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
        if($this->hasShadow())
        {
            $this->createShadow($canvas);
        }

        if($this->hasStroke())
        {
            $this->createStroke($canvas);
        }

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

    public function createShadow($canvas)
    {
        $color = imagecolorallocatealpha($canvas,
            $this->getShadowColor()->getR(),
            $this->getShadowColor()->getG(),
            $this->getShadowColor()->getB(),
            50
        );

        $x1 = $this->getX() + $this->getShadowDist();
        $y1 = $this->getY() + $this->getShadowDist();
        $x2 = $x1 + $this->getWidth();
        $y2 = $y1 + $this->getHeight();

        imagefilledrectangle($canvas, $x1, $y1, $x2, $y2, $color);
    }

    public function createStroke($canvas)
    {
        $color = imagecolorallocate($canvas,
            $this->getShadowColor()->getR(),
            $this->getShadowColor()->getG(),
            $this->getShadowColor()->getB()
        );

        $x1 = $this->getX() - $this->getStroke()->getWidth();
        $y1 = $this->getY() - $this->getStroke()->getWidth();
        $x2 = $this->getX() + $this->getStroke()->getWidth() + $this->getWidth();
        $y2 = $this->getY() + $this->getStroke()->getWidth() + $this->getHeight();

        imagefilledrectangle($canvas, $x1, $y1, $x2, $y2, $color);
    }

    public function getSvg()
    {
        $stroke = $this->getStroke();
        $shadow = $this->getShadowColor();

        $svg = '';

        $svg .= "\r\n" . '<rect';
        $svg .= "\r\n" . ' fill="' . $this->getFill()->getHex() . '"';

        if(isset($stroke))
        {
            $svg .= "\r\n" . ' stroke="' . $stroke->getColor()->getHex() . '"';
            $svg .= "\r\n" . ' stroke-width="' . $stroke->getWidth() . '"';
        }

        if(isset($shadow))
        {
            $svg .= "\r\n" . ' style="shadow:' . $shadow->getHex() . ';shadow-dist:' . $this->getShadowDist() . 'px;"';
        }

        $svg .= "\r\n" . ' x="' . $this->getX() . '"';
        $svg .= "\r\n" . ' y="' . $this->getY() . '"';
        $svg .= "\r\n" . ' width="' . $this->getWidth() . '"';
        $svg .= "\r\n" . ' height="' . $this->getHeight() . '"';
        $svg .= "\r\n" . ' id="' . $this->getId() . '"';
        $svg .= "\r\n" . '/>';
        return $svg;
    }
}
