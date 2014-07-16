<?php

class GfxRectangle extends GfxShape
{
    public function __construct()
    {
    }

    public function render()
    {
        echo 'Now I\'m here ... ' . $this->x . '/' . $this->y . "\n";
    }

}


?>
