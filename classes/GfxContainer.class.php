<?php
/**
 * Comment here
 *
 * User: thomas.hummel@mediadecision.com
 * Date: 17/07/2014
 * Time: 07:30
 */

class GfxContainer # implements Renderable ;)
{
    private $sId;
    protected $elements;
    private $target;

    public function __construct()
    {
        $this->elements = array();
    }

    public function setId($sId)
    {
        $this->sId =$sId;
    }

    public function getId()
    {
        return $this->sId;
    }

    public function addElement($element)
    {
        if(is_a($element, 'GfxComponent')) {
            $this->elements[] = $element;
            echo 'Success!';
        } else {
            echo 'No!';
        }
    }

    public function render()
    {

    }

    public function setTarget($target)
    {
        if(!in_array($target, $this->allowedTargets)) {
            die('What a terrible death!');
        } else {
            $this->target = $target;
        }
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function __toString()
    {
        $string = '';
        foreach($this->elements AS $element) {
            $string .= print_r($element, true);
        }
        return $string;
    }
}
