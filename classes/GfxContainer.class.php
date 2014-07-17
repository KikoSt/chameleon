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
}
?>
