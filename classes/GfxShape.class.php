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
    public function __construct()
    {
        parent::__construct();
        $this->color = new GfxColor();
    }

    public function create($svgRootNode)
    {
        parent::create($svgRootNode);
    }

}
