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

        $r = $this->getFill()->getR();
        $g = $this->getFill()->getG();
        $b = $this->getFill()->getB();

        $fill = $rect->addFill($r, $g, $b);
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

    }

}
