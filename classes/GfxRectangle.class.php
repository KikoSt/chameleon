<?php

class GfxRectangle extends GfxShape
{
    public function __construct()
    {
    }

    public function render($canvas)
    {
        echo 'Now I\'m here ... ' . $this->x . '/' . $this->y . "\n";
    }


    public function create()
    {
        $rect = new SWFShape();

        $r = $this->getR();
        $g = $this->getG();
        $b = $this->getB();

        $fill = $rect->addFill($r, $g, $b);
        $rect->setRightFill($fill);

        // calculation of top left and bottom right values might change when the transformationCenter is changed
        // (top left of rect for now)
        $x1 = $this->getX();
        $y1 = $this->getY();
        $x2 = $this->getX() + $this->getWidth();
        $y2 = $this->getY() + $this->getHeight();

        $rect->movePenTo($x1, $y1);
        $rect->drawLineTo($x1, $y2);
        $rect->drawLineTo($x2, $y2);
        $rect->drawLineTo($x2, $y1);
        $rect->drawLineTo($x1, $y1);

        return $rect;
    }

}


?>
