<?php

class GfxAnimation
{
    private $targetComponent;
    private $targetAttribute;
    private $frameRate;
    private $numFrames;

    public function __construct(GfxComponent $targetComponent)
    {
        $this->targetComponent = $targetComponent;
        $this->targetAttribute = null;
        $this->frameRate = 0;
        $this->numFrames = 0;
    }

    public function setframeRate($frameRate)
    {
        $this->frameRate = $frameRate;
    }

    public function getFrameRate()
    {
        return $this->frameRate;
    }

    public function setNumFrames($numFrames)
    {
        $this->numFrames = $numFrames;
    }

    public function getNumFames()
    {
        return $this->numFrames;
    }
}

?>
