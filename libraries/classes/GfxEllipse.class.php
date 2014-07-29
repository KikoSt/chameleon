<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 11:38
 */

class GfxEllipse extends GfxShape
{
    private $fYRadius;
    private $fXRadius;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getXRadius()
    {
        return $this->fXRadius;
    }

    /**
     * @param mixed $fXRadius
     */
    public function setXRadius($fXRadius)
    {
        $this->fXRadius = $fXRadius;
    }

    /**
     * @return mixed
     */
    public function getYRadius()
    {
        return $this->fYRadius;
    }

    /**
     * @param mixed $fYRadius
     */
    public function setYRadius($fYRadius)
    {
        $this->fYRadius = $fYRadius;
    }


}
