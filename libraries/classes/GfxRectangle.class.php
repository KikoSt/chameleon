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

        $sprite = new SWFSprite();
        $sprite->setFrames($this->getContainer()->getFramerate());

        if($this->shadowEnabled() && $this->getShadow()->getColor() instanceof GfxColor)
        {
            $shadow = new SWFShape();
            $shadowX1 = $this->getX() + $this->getShadow()->getDist();
            $shadowY1 = $this->getY() + $this->getShadow()->getDist();
            $shadowX2 = $shadowX1 + $this->getWidth();
            $shadowY2 = $shadowY1 + $this->getHeight();

            $shadowX1 = -($this->getWidth() /  2) + $this->getShadow()->getDist();
            $shadowY1 = -($this->getHeight() / 2) + $this->getShadow()->getDist();
            $shadowX2 = ($this->getWidth() /   2) + $this->getShadow()->getDist();
            $shadowY2 = ($this->getHeight() /  2) + $this->getShadow()->getDist();

            $shadowColor = $this->getShadow()->getColor();
            $shadowFill = $shadow->addFill($shadowColor->getR(), $shadowColor->getG(), $shadowColor->getB(), 128);
            $shadow->setRightFill($shadowFill);

            $shadow->movePenTo($shadowX1, $shadowY1);
            $shadow->drawLineTo($shadowX1, $shadowY2);
            $shadow->drawLineTo($shadowX2, $shadowY2);
            $shadow->drawLineTo($shadowX2, $shadowY1);
            $shadow->drawLineTo($shadowX1, $shadowY1);

            $shandle = $sprite->add($shadow);

            $shandle->moveTo($this->getX() + $this->getWidth() / 2, $this->getY() + $this->getHeight() / 2);
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

        $x1 = -($this->getWidth() / 2);
        $y1 = -($this->getHeight() / 2);
        $x2 = ($this->getWidth() / 2);
        $y2 = ($this->getHeight() / 2);

        if($this->strokeEnabled() && $this->getStroke() instanceof GfxStroke)
        {
            $rect->setLine(1, 0, 0, 0);
        }

        $rect->movePenTo($x1, $y1);
        $rect->drawLineTo($x1, $y2);
        $rect->drawLineTo($x2, $y2);
        $rect->drawLineTo($x2, $y1);
        $rect->drawLineTo($x1, $y1);

        $handle = $sprite->add($rect);

        if($this->drawCenter)
        {
            $chandle = $this->drawCenter($sprite);
        }

        $handle->moveTo($this->getX() + $this->getWidth() / 2, $this->getY() + $this->getHeight() / 2);


        /**
         *  Prepare actual animation
        **/
        if(count($this->getAnimations()) > 0)
        {
            $handleList = array();
            if(isset($chandle))
            {
                $handleList['centerHandle'] = $chandle;
            }
            if(isset($shandle))
            {
                $handleList['shadowHandle'] = $shandle;
            }
            $handleList['handle'] = $handle;
            $sprite = $this->swfAnimate($handleList, $sprite);
        }
        /**
         *  Animation done!
        **/

        $handle = $canvas->add($sprite);

        // absolutely required, otherwise nothing will be displayed
        $sprite->nextFrame();

        return $canvas;
    }



    public function renderGIF($frame)
    {
        $rectangle = new ImagickDraw();

        if($this->getFill()->getR() !== null)
        {
            $color = new ImagickPixel($this->getFill()->getHex());
            $rectangle->setFillColor($color);
        }
        else
        {
            $color = new ImagickPixel($this->getStroke()->getColor()->getHex());
            $rectangle->setFillcolor($color);
        }

        if($this->shadowEnabled() && $this->hasShadow())
        {
            $shadow = $this->createShadow();
            $frame->drawimage($shadow);
        }

        if($this->strokeEnabled() && $this->hasStroke())
        {
            $this->createStroke($rectangle);
        }

        $x2 = $this->getX() + $this->getWidth();
        $y2 = $this->getY() + $this->getHeight();
        $rectangle->rectangle($this->getX(), $this->getY(), $x2, $y2);

        $frame->drawImage($rectangle);

        return $frame;
    }

    // TODO: rename those functions in order to reflect the fact that they will only work for GIF!!
    public function createShadow()
    {
        $color = new ImagickPixel($this->getShadow()->getColor()->getHex());

        $x1 = $this->getX() + $this->getShadow()->getDist();
        $y1 = $this->getY() + $this->getShadow()->getDist();
        $x2 = $x1 + $this->getWidth();
        $y2 = $y1 + $this->getHeight();

        $shadow = new ImagickDraw();
        $shadow->setFillColor($color);
        $shadow->setfillopacity(0.5);
        $shadow->rectangle($x1, $y1, $x2, $y2);

        return $shadow;
    }

    public function createStroke($rectangle)
    {
        $color = new ImagickPixel($this->getStroke()->getColor()->getHex());
        $rectangle->setStrokeWidth($this->getStroke()->getWidth());
        $rectangle->setStrokeColor($color);
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

        if(count($this->getAnimations()) > 0)
        {
            $aniString  = ' cmeo:animation="';
            $aniString .= $this->serializeAnimations();
            $aniString .= '"';
            $svg .= $aniString;
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
