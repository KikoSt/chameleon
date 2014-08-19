<?php

/**
 * Class GfxAnimation
 *
 *
 */
class GfxAnimation
{
    public $target;
    public $attributeName;
    public $attributeType;
    public $duration;
    public $values;

    public function getAttributeName()
    {
        return $this->attributeName;
    }

    public function setAttributeName($attributeName)
    {
        $this->attributeName = $attributeName;
    }

    public function getAttributeType()
    {
        return $this->attributeType;
    }

    public function setAttributeType($attributeType)
    {
        $this->attributeType = $attributeType;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setValues($values)
    {
        $this->values = $values;
    }


} 