<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 11:39
 */

class GfxPoly extends GfxShape
{
    private $aCoordinate;

    /**
     * @return mixed
     */
    public function getCoordinate()
    {
        return $this->aCoordinate;
    }

    /**
     * @param mixed $aCoordinate
     */
    public function setCoordinate(Array $aCoordinate)
    {
        $this->aCoordinate = $aCoordinate;
    }

} 