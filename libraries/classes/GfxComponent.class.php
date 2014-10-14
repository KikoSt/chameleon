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
    private $shadow;
    private $shadowEnabled, $strokeEnabled;
    private $linkUrl;
    private $shadowColor;
    private $shadowDist;

    private $cmeoRef;
    private $cmeoLink;

    private $container;

    public function __construct(GfxContainer $container)
    {
        $this->x      = 0;
        $this->y      = 0;
        $this->width  = 0;
        $this->height = 0;
        $this->container = $container;
    }

    public function updateData()
    {
    }

    public function create($svgRootNode)
    {
        $attr = $svgRootNode->attributes();

        $this->setX((float) $attr->x);
        $this->setY((float) $attr->y);
        $this->setWidth((float) $attr->width);
        $this->setHeight((float) $attr->height);

        $this->setId((string) $attr->id);

        if((string) $svgRootNode->attributes()->style !== '')
        {
            $styles = array();
            $style = $svgRootNode->attributes()->style;
            $style = rtrim($style, ';');
            $stylesList = explode(';', $style);
            foreach($stylesList AS $curStyle)
            {
                list($curKey, $curValue) = explode(':', $curStyle);
                $styles[$curKey] = $curValue;
            }

            if(array_key_exists('stroke', $styles))
            {
                $strokeColor = new GfxColor($styles['stroke']);
                $strokeWidth = (int) $styles['stroke-width'];
                $stroke = new GfxStroke($strokeColor, $strokeWidth);
                $stroke->setColor($strokeColor);
                $stroke->setWidth($strokeWidth);
                $this->setStroke($stroke);
                $this->enableStroke();
            }
            else if($attr->stroke !== null && (int) $attr->{'stroke-width'} !== 0)
            {
                $strokeColor = new GfxColor($attr->stroke);
                $strokeWidth = (int) $attr->{'stroke-width'};
                $stroke = new GfxStroke($strokeColor, $strokeWidth);
                $stroke->setColor($strokeColor);
                $stroke->setWidth($strokeWidth);
                $this->setStroke($stroke);
                $this->enableStroke();
            }

            if(array_key_exists('shadow', $styles))
            {
                $shadowColor = new GfxColor($styles['shadow']);
                $shadowDist = (int) $styles['shadow-dist'];
                $shadow = new GfxShadow($shadowColor, $shadowDist);
                $this->setShadow($shadow);
                $this->enableShadow();
            }
        }

        $ref =  (string) $svgRootNode->attributes('cmeo', true)->ref;
        $link = (string) $svgRootNode->attributes('cmeo', true)->link;
        if(!empty($ref))
        {
            $this->getContainer()->registerDataUpdate($ref, $this);
            $this->setRef($ref);
            $this->setCmeoRef($ref);
        }
        if(!empty($link))
        {
            $this->getContainer()->registerDataUpdate($link, $this);
            $this->setCmeoLink($link);
            $this->setLink($link);
        }
    }

    public function hasShadow()
    {
        $shadow = $this->getShadow();

        if(!empty($shadow))
        {
            return true;
        }

        //return false as fallback
        return false;
    }

    public function hasStroke()
    {
        $stroke = $this->getStroke();

        if(isset($stroke))
        {
            return true;
        }

        //return false as fallback
        return false;
    }

    public function disableStroke()
    {
        $this->strokeEnabled = false;
    }

    public function enableStroke()
    {
        $this->strokeEnabled = true;
    }

    public function disableShadow()
    {
        $this->shadowEnabled = false;
    }

    public function enableShadow()
    {
        $this->shadowEnabled = true;
    }

    protected function addClickableLink($canvas)
    {
        if(!empty($this->getLinkUrl()))
        {
            $hit = new SWFShape();
            $hit->setRightFill($hit->addFill(255,0,0));
            $hit->movePenTo(0, 0);
            $hit->drawLineTo($this->getWidth(), 0);
            $hit->drawLineTo($this->getWidth(), $this->getHeight());
            $hit->drawLineTo(0, $this->getHeight());
            $hit->drawLineTo(0, 0);

            $button = new SWFButton();
            $button->addShape($hit, SWFBUTTON_HIT);
            $linkUrl = $this->getLinkUrl();
            $button->addAction(new SWFAction("getURL('$linkUrl','_blank');"), SWFBUTTON_MOUSEUP);
            $handle = $canvas->add($button);
            $handle->moveTo($this->getX(), $this->getY());
        }
        return $canvas;
    }

    public function getShadow()
    {
        return $this->shadow;
    }

    public function setShadow(GfxShadow $shadow)
    {
        $this->shadow = $shadow;
    }

    public function getStroke()
    {
        return $this->stroke;
    }

    public function setStroke(GfxStroke $stroke)
    {
        $this->stroke = $stroke;
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

    public function getSvg()
    {
        return '';
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

    /**
     * Get container.
     *
     * @return container.
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set container.
     *
     * @param container the value to set.
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getCmeoLink()
    {
        return $this->cmeoLink;
    }

    /**
     * @param mixed $cmeoLink
     */
    public function setCmeoLink($cmeoLink)
    {
        $this->cmeoLink = $cmeoLink;
    }

    /**
     * @return mixed
     */
    public function getCmeoRef()
    {
        return $this->cmeoRef;
    }

    /**
     * @param mixed $cmeoRef
     */
    public function setCmeoRef($cmeoRef)
    {
        $this->cmeoRef = $cmeoRef;
    }

    /**
     * @return mixed
     */
    public function getRef()
    {
        return $this->cmeoRef;
    }

    /**
     * @param mixed $cmeoRef
     */
    public function setRef($cmeoRef)
    {
        $this->cmeoRef = $cmeoRef;
    }

    public function shadowEnabled()
    {
        return $this->shadowEnabled;
    }

    public function strokeEnabled()
    {
        return $this->strokeEnabled;
    }

}
