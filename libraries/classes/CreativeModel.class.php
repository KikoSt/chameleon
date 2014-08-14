<?php

class CreativeModel
{
    private $width;
    private $height;
    private $swfPath;
    private $gifPath;
    private $productId;


    /**
     * Get width.
     *
     * @return width.
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width.
     *
     * @param width the value to set.
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get height.
     *
     * @return height.
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set height.
     *
     * @param height the value to set.
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get swfPath.
     *
     * @return swfPath.
     */
    public function getSwfPath()
    {
        return $this->swfPath;
    }

    /**
     * Set swfPath.
     *
     * @param swfPath the value to set.
     */
    public function setSwfPath($swfPath)
    {
        $this->swfPath = $swfPath;
    }

    /**
     * Get gifPath.
     *
     * @return gifPath.
     */
    public function getGifPath()
    {
        return $this->gifPath;
    }

    /**
     * Set gifPath.
     *
     * @param gifPath the value to set.
     */
    public function setGifPath($gifPath)
    {
        $this->gifPath = $gifPath;
    }

    /**
     * Get productId.
     *
     * @return productId.
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set productId.
     *
     * @param productId the value to set.
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }
}

