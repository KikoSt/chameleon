<?php

/**
 * Class GfxAnimation
 *
 *
 */
class GfxAnimation
{
    private $duration;
    private $targets;
    private $keyframes;

    public function __construct()
    {
        $this->keyframes = array();
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function addTarget($attribute, $stepsize)
    {
        $target = new AnimationComponent($attribute, $stepsize);
        $this->targets[] = $target;
    }

    public function getTargets()
    {
        return $this->targets;
    }

    public function addKeyframe($framenum)
    {
        if(!in_array($framenum, $this->animationKeyframe))
        {
            $this->animationKeyframes[] = $framenum;
        }
        $this->animationKeyframes = sort($this->animationKeyframes);
    }
}

class AnimationComponent
{
    private $attribute;
    private $stepsize;

    public function __construct($attribute, $stepsize)
    {
        $this->attribute = $attribute;
        $this->stepsize = $stepsize;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function getStepsize()
    {
        return $this->stepsize;
    }
}


