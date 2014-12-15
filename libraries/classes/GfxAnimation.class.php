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

    public function __construct()
    {
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


