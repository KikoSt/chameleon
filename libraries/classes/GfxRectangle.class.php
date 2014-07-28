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
        // the sprite is used as a container for links, strokes (at least until stroke implementation uses the SWF
        // strokes here

        $sprite = new SWFSprite();
        $sprite->setFrames(30);
        $rect = new SWFShape();

        // if($this->getStroke() !== null)
        // {
        //     $strokeWidth = $this->getStroke()->getWidth();
        //     // TODO: draw the shape directly here, do NOT use another GfxRectangle (since we do not want another
        //     // sprite!
        //     $stroke = new GfxRectangle();
        //     $stroke->setWidth($this->getWidth() + ($strokeWidth * 2));
        //     $stroke->setHeight($this->getHeight() + ($strokeWidth * 2));
        //     $stroke->setX($this->getX() - $strokeWidth);
        //     $stroke->setY($this->getY() - $strokeWidth);
        //     $stroke->setFill($this->getStroke()->getColor());
        //     $stroke->renderSWF($canvas);

        // }

        if($this->getShadowColor() !== null)
        {
            $shadow = new SWFShape();
            // BLACK with 50% opacity for now
            $shadowFill = $shadow->addFill(0, 0, 0, 128);
            $shadow->setRightFill($shadowFill);

            $shadowX1 = $this->getX() + $this->getShadowDist();
            $shadowY1 = $this->getY() + $this->getShadowDist();
            $shadowX2 = $shadowX1 + $this->getWidth();
            $shadowY2 = $shadowY1 + $this->getHeight();

            $shadow->movePenTo($shadowX1, $shadowY1);
            $shadow->drawLineTo($shadowX1, $shadowY2);
            $shadow->drawLineTo($shadowX2, $shadowY2);
            $shadow->drawLineTo($shadowX2, $shadowY1);
            $shadow->drawLineTo($shadowX1, $shadowY1);

            $handle = $sprite->add($shadow);
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

        $handle = $sprite->add($rect);

        // this is absolutely required
        $sprite->nextFrame();

        $handle = $canvas->add($sprite);

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

}
