<?php
/**
 * Comment here
 *
 * User: thomas.hummel@mediadecision.com
 * Date: 17/07/2014
 * Time: 09:01
 */

class GfxShape extends GfxComponent
{
    protected $color;
    private $fWidth;
    private $fHeight;

    public function setFillColor($sColorHex)
    {

    }

    public function setWidth($fWidth)
    {
        $this->fWidth = $fWidth;
    }

    public function setHeight($fHeight)
    {
        $this->fHeight = $fHeight;
    }
}
