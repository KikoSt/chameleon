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

        $attr = $svgRootNode->attributes();

        $fill = new GfxColor();
        $fill->setHex((string) $attr->fill);
        $this->setFill($fill);

        // $stroke = new GfxStroke();
        // $stroke->setHex((string) $attr->stroke);
        // $this->setStroke($stroke);

    }

}
