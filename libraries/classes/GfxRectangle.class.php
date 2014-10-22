<?php
/**
 * Handles the creation of rectangles while rendering GIF and SWF
 *
 * User: thomas.hummel@mediadecision.com
 * Date: 17/07/2014
 * Time: 09:01
 */

class GfxRectangle extends GfxShape
{
    public function __construct(GfxContainer $container)
    {
        parent::__construct($container);
    }


    public function updateData()
    {
        parent::updateData();

        if($this->getContainer()->getProductData())
        {
            if(!empty($this->getLinkUrl()))
            {
                echo "\n[" . $this->getLinkUrl() . "]\n";
            }
        }
    }



    /**
     * renderSWF
     *
     * renders itself inside of the swf canvas and passes the modified canvas back;
     *
     * @param mixed $canvas
     * @access public
     * @return void
     */
    public function renderSWF($canvas)
    {
        $rect = new SWFShape();

        if($this->strokeEnabled() && $this->getStroke() instanceof GfxColor)
        {
            $strokeWidth = $this->getStroke()->getWidth();
            $stroke = new GfxRectangle($this->getContainer());
            $stroke->setWidth($this->getWidth() + ($strokeWidth * 2));
            $stroke->setHeight($this->getHeight() + ($strokeWidth * 2));
            $stroke->setX($this->getX() - $strokeWidth);
            $stroke->setY($this->getY() - $strokeWidth);
            $stroke->setFill($this->getStroke()->getColor());
            $stroke->renderSWF($canvas);

        }

        if($this->shadowEnabled() && $this->getShadow()->getColor() instanceof GfxColor)
        {
            $shadow = new GfxRectangle($this->getContainer());
            $shadow->setWidth($this->getWidth());
            $shadow->setHeight($this->getHeight());
            $shadow->setX($this->getX() + (int) $this->getShadow()->getDist());
            $shadow->setY($this->getY() + (int) $this->getShadow()->getDist());
            $shadowColor = $this->getShadow()->getColor();
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
        if($this->shadowEnabled() && $this->hasShadow())
        {
            $this->createShadow($canvas);
        }

        if($this->strokeEnabled() && $this->hasStroke())
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

    // TODO: rename those functions in order to reflect the fact that they will only work for GIF!!
    public function createShadow($canvas)
    {
        $color = imagecolorallocatealpha($canvas,
            $this->getShadow()->getColor()->getR(),
            $this->getShadow()->getColor()->getG(),
            $this->getShadow()->getColor()->getB(),
            50
        );

        $x1 = $this->getX() + $this->getShadow()->getDist();
        $y1 = $this->getY() + $this->getShadow()->getDist();

        $x2 = $x1 + $this->getWidth();
        $y2 = $y1 + $this->getHeight();

        imagefilledrectangle($canvas, $x1, $y1, $x2, $y2, $color);
    }

    public function createStroke($canvas)
    {
        $color = imagecolorallocate($canvas,
            $this->getStroke()->getColor()->getR(),
            $this->getStroke()->getColor()->getG(),
            $this->getStroke()->getColor()->getB()
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
        $shadow = $this->getShadow();

        $svg = '';

        $svg .= "\r\n" . '<rect';
        $svg .= "\r\n" . ' cmeo:link="' . $this->getCmeoLink() . '"';
        $svg .= "\r\n" . ' cmeo:editGroup="' . $this->getEditGroup(). '"';
        $svg .= "\r\n" . ' fill="' . $this->getFill()->getHex() . '"';

        if(isset($stroke))
        {
            $svg .= "\r\n" . ' stroke="' . $stroke->getColor()->getHex() . '"';
            $svg .= "\r\n" . ' stroke-width="' . $stroke->getWidth() . '"';
        }

        if(isset($shadow))
        {
            $svg .= "\r\n" . ' style="shadow:' . $shadow->getColor()->getHex() . ';shadow-dist:' . $shadow->getDist() . 'px;"';
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
