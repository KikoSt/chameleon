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
    private $editGroup;

    private $animationList;

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

        // DEBUG for swf
        $this->drawCenter = false;

        $this->animationList = array();
    }

    public function addAnimation($animationDefinition)
    {
        $animationDefinition = str_replace('[', '', $animationDefinition);
        $aniDefs = explode(']', $animationDefinition);
        foreach($aniDefs AS $aniDef)
        {
            if(!empty($aniDef))
            {
                $ani = new GfxAnimation();
                $defs = explode(':', $aniDef);
                $ani->setDuration($defs[0]);

                $targets = explode('|', $defs[1]);

                foreach($targets AS $target)
                {
                    $aniComponent = explode('/', $target);
                    if(!empty($aniComponent[0]))
                    {
                        $ani->addTarget($aniComponent[0], $aniComponent[1]);
                    }
                }
                $this->animationList[] = $ani;
            }
        }
    }

    public function setAnimation($animationDefinition)
    {
        $this->clearAnimations();
        $this->addAnimation($animationDefinition);
    }

    public function getFrameDuration()
    {
        $frameDuration = 0;
        foreach($this->animationList AS $animation)
        {
            $frameDuration += $animation->getDuration();
        }
        return $frameDuration;
    }

    public function clearAnimations()
    {
        $this->animationList = array();
    }

    public function serializeAnimations()
    {
        $aniString = '';
        foreach($this->getAnimations() AS $animation)
        {
            $aniString .= '[';

            $aniString .= $animation->getDuration() . ':';

            foreach($animation->getTargets() AS $target)
            {
                $aniString .= $target->getAttribute() . '/' . $target->getStepsize() . '|';
            }
            $aniString = rtrim($aniString, '|');
            $aniString .= ']';
        }

        return $aniString;
    }

    public function getAnimations()
    {
        return $this->animationList;
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

        $ref        = (string) $svgRootNode->attributes('cmeo', true)->ref;
        $link       = (string) $svgRootNode->attributes('cmeo', true)->link;
        $editGroup  = (int)    $svgRootNode->attributes('cmeo', true)->editGroup;
        $animations = (string) $svgRootNode->attributes('cmeo', true)->animation;

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
        if(!empty($editGroup))
        {
            $this->setEditGroup($editGroup);
        }
        if(!empty($animations))
        {
            $this->addAnimation($animations);
        }
    }

//    public function clearAnimations()
//    {
//        $this->animationList = array();
//    }

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



    public function getFilepath($filename)
    {
        $filename = ltrim($filename, '/');
        $filepath = $filename;
        if(substr($filepath, 0, 4) !== 'http')
        {
            $filepath = BASE_DIR . '/' . $filepath;
        }
        return $filepath;
    }


    /***
     *   function swfAnimate
     *
     * Animate the swf components. For each swf component, we got a specific handle stored in the handleList;
     * Handles can be of:
     * - the component itself
     * - it's shadow
     * - it's outline (image, for this will actually be another rectangle placed behind the bitmap)
     *
     * Cycling through all target attributes (x, y, width, height, rotation for now) for each object,
     * modifying the respective attribute by the stored stepsize
     *
     *
     ***/
    protected function swfAnimate($handleList, $sprite)
    {
        foreach($this->getAnimations() AS $animation)
        {
            $duration = $animation->getDuration();
            $targets  = $animation->getTargets();
            // step through all steps of the animation
            for($i=0; $i<$duration; $i++)
            {
                // target each required target (attribute)
                foreach($targets AS $target)
                {
                    $targetAttribute = $target->getAttribute();
                    $stepsize        = $target->getStepsize();
                    // and all objects
                    foreach($handleList AS $handle)
                    {
                        switch($targetAttribute)
                        {
                            case 'x':
                                $handle->move($stepsize, 0);
                                break;
                            case 'y':
                                $handle->move(0, $stepsize);
                                break;
                            case 'w':
                                $handle->scale($stepsize, 1);
                                break;
                            case 'h':
                                $handle->scale(1, $stepsize);
                                break;
                            case 'r':
                                $handle->rotate($stepsize);
                                break;
                            case 'p':
                                // pause
                                break;
                        }
                    }
                }
                $sprite->nextFrame();
            }
        }
        return $sprite;
    }



    protected function drawCenter($sprite)
    {
        $center = new SWFShape();
        $center->setLine(1, 0, 0, 0);
        $center->movePenTo(-5, -5);
        $center->drawLineTo(5, 5);
        $center->movePenTo(-5, 5);
        $center->drawLineTo(5, -5);

        $chandle = $sprite->add($center);
        $chandle->moveTo($this->getX() + $this->getWidth() / 2, $this->getY());

        return $chandle;
    }


    protected function addClickableLink($sprite)
    {
        if(!empty($this->getLinkUrl()))
        {
            $hit = new SWFShape();
            $hit->setRightFill($hit->addFill(255,0,0));

            $x1 = -($this->getWidth() / 2);
            $y1 = -($this->getHeight() / 2);
            $x2 = $this->getWidth() / 2;
            $y2 = $this->getHeight() / 2;

            if($this instanceof GfxText)
            {
                $y1 = -$this->getHeight();
                $y2 = $this->getHeight();
            }

            $hit->movePenTo($x1, $y1);
            $hit->drawLineTo($x2, $y1);
            $hit->drawLineTo($x2, $y2);
            $hit->drawLineTo($x1, $y2);
            $hit->drawLineTo($x1, $y1);

            $button = new SWFButton();
            $button->addShape($hit, SWFBUTTON_HIT);
            // $button->addShape($hit, SWFBUTTON_UP);
            $linkUrl = $this->getLinkUrl();
            $button->addAction(new SWFAction("getURL('$linkUrl','_blank');"), SWFBUTTON_MOUSEUP);
            $lhandle = $sprite->add($button);
            return $lhandle;
        }
        return(false);
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

    public function setEditGroup($editGroup)
    {
        $this->editGroup = $editGroup;
    }

    public function getEditGroup()
    {
        return $this->editGroup;
    }
}
