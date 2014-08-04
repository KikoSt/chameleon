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

    /**
     * setframeRate
     *
     * @param int $frameRate the framerate for this animation
     * @access public
     * @return void
     */
    public function setframeRate($frameRate)
    {
        $this->frameRate = $frameRate;
    }

    /**
     * getFrameRate
     *
     * @access public
     * @return int frameRate the framerate for this animation
     */
     // TODO: not necessarily ... the framerate will most likely be defined globally by the movie itself,
     // components cannot have differen framerates!
    public function getFrameRate()
    {
        return $this->frameRate;
    }

    /**
     * setNumFrames
     *
     * @param mixed $numFrames
     * @param int numFrames the number of frames the current animation should last
     * @access public
     * @return void
     */
    public function setNumFrames($numFrames)
    {
        $this->numFrames = $numFrames;
    }

    /**
     * getNumFrames
     *
     * @access public
     * @return numFrames int the number of frames the current animation should last
     */
    public function getNumFrames()
    {
        return $this->numFrames;
    }

    /**
     * Get targetComponent.
     *
     * @return targetComponent.
     */
    public function getTargetComponent()
    {
        return $this->targetComponent;
    }

    /**
     * Set targetComponent.
     *
     * @param targetComponent the value to set.
     */
    public function setTargetComponent($targetComponent)
    {
        $this->targetComponent = $targetComponent;
    }
}

?>
