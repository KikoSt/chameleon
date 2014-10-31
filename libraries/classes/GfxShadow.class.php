<?php

/**
 * GfxShadow
 *
 * container class for shadows used by GfxComponents
 *
 * @package
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <toby@php.net>
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class GfxShadow
{
    private $color;
    private $dist;

    public function __construct(GfxColor $color, $dist)
    {
        $this->setColor($color);
        $this->setDist($dist);
    }

    public function setColor(GfxColor $color)
    {
        $this->color = $color;
    }

    public function setDist($dist)
    {
        $this->dist = $dist;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getDist()
    {
        return $this->dist;
    }
}
