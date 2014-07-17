<?php
class GfxContainer
{
    protected $elements;

    public function __construct()
    {
        $this->elements = array();
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
}


?>
