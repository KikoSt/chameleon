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
    private $linecap;
    private $linejoin;
    private $dasharray;

    /**
     * @param GfxColor $color
     * @param $width
     * @param null $linecap
     * @param null $linejoin
     * @param null $dasharray
     */
    public function __construct(GfxColor $color, $width, $linecap = null, $linejoin = null, $dasharray = null)
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

    /**
     * @return mixed
     */
    public function getDasharray()
    {
        return $this->dasharray;
    }

    /**
     * @param mixed $dasharray
     */
    public function setDasharray($dasharray)
    {
        $this->dasharray = $dasharray;
    }

    /**
     * @return mixed
     */
    public function getLinecap()
    {
        return $this->linecap;
    }

    /**
     * @param mixed $linecap
     */
    public function setLinecap($linecap)
    {
        $this->linecap = $linecap;
    }

    /**
     * @return mixed
     */
    public function getLinejoin()
    {
        return $this->linejoin;
    }

    /**
     * @param mixed $linejoin
     */
    public function setLinejoin($linejoin)
    {
        $this->linejoin = $linejoin;
    }

}
