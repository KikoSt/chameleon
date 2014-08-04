<?php

/**
 * Stroke
 *
 * @package
 * @version $id$
 * @copyright 2014 Mediadecision GmbH
 * @author Christoph Starkmann <christoph.starkmann@mediadecision.com>
 * @license commercial
 */
class GfxStroke
{
    private $color;
    private $width;

    /**
     * __construct
     *
     * @param Color $color
     * @param int $width
     * @access public
     * @return void
     */
    public function __construct(GfxColor $color, $width)
    {
        $this->setColor($color);
        $this->setWidth($width);
    }

    /**
     * setWidth
     *
     * @param int $width
     * @access public
     * @return void
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * getWidth
     *
     * @access public
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * setColor
     *
     * @param Color $color
     * @access public
     * @return void
     */
    public function setColor(GfxColor $color)
    {
        $this->color = $color;
    }

    /**
     * getColor
     *
     * @access public
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    public function getHex()
    {
        return '#' . (sprintf('%02x', $this->getR())) . sprintf('%02x', $this->getG()) . sprintf('%02x', $this->getB());
    }
}
