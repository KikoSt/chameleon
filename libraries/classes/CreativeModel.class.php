<?php

class CreativeModel
{
    private $dimX;
    private $dimY;
    private $swfPath;
    private $gifPath;
    private $swfFileName;
    private $gifFileName;
    private $swfContent;
    private $gifContent;
    private $idProduct;


    public function __construct()
    {
    }



    public function prepareTransmission()
    {
        // check if information on both files are available AND both files are accessible
        if(empty($this->swfPath) || !($handle = fopen($this->getSwfPath(), 'r')))
        {
            throw new Exception('No information on swf file provided');
        }
        fclose($handle);
        if(empty($this->gifPath) || !($handle = fopen($this->getGifPath(), 'r')))
        {
            throw new Exception('No information on gif file provided');
        }
        fclose($handle);

        $this->setSwfBin(base64_encode(file_get_contents($this->getSwfPath())));
        $this->setGifBin(base64_encode(file_get_contents($this->getGifPath())));

        // $this->setSwfBin(base64_encode('')); // base64_encode(file_get_contents($this->getSwfPath())));
        // $this->setGifBin(base64_encode('')); // base64_encode(file_get_contents($this->getGifPath())));
    }


    /**
     * Get width.
     *
     * @return width.
     */
    public function getWidth()
    {
        return $this->dimX;
    }

    /**
     * Set width.
     *
     * @param width the value to set.
     */
    public function setWidth($width)
    {
        $this->dimX = $width;
    }

    /**
     * Get height.
     *
     * @return height.
     */
    public function getHeight()
    {
        return $this->dimY;
    }

    /**
     * Set height.
     *
     * @param height the value to set.
     */
    public function setHeight($height)
    {
        $this->dimY = $height;
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
        return $this->idProduct;
    }

    /**
     * Set productId.
     *
     * @param productId the value to set.
     */
    public function setProductId($productId)
    {
        $this->idProduct = $productId;
    }

    /**
     * Get swfBin.
     *
     * @return swfBin.
     */
    public function getSwfBin()
    {
        return $this->swfContent;
    }

    /**
     * Set swfBin.
     *
     * @param swfBin the value to set.
     */
    public function setSwfBin($swfBin)
    {
        $this->flashContent = $swfBin;
    }

    /**
     * Get gifBin.
     *
     * @return gifBin.
     */
    public function getGifBin()
    {
        return $this->gifContent;
    }

    /**
     * Set gifBin.
     *
     * @param gifBin the value to set.
     */
    public function setGifBin($gifBin)
    {
        $this->gifContent = $gifBin;
    }

    public function setFileName($filename)
    {
        $this->setSwfFilename($filename . '.swf');
        $this->setGifFilename($filename . '.gif');
    }

    /**
     * Get swfFileName.
     *
     * @return swfFileName.
     */
    public function getSwfFilename()
    {
        return $this->swfFileName;
    }

    /**
     * Set swfFileName.
     *
     * @param swfFileName the value to set.
     */
    private function setSwfFilename($swfFilename)
    {
        $this->swfFileName = $swfFilename;
    }

    /**
     * Get gifFileName.
     *
     * @return gifFileName.
     */
    public function getGifFilename()
    {
        return $this->gifFileName;
    }

    /**
     * Set gifFileName.
     *
     * @param gifFileName the value to set.
     */
    private function setGifFilename($gifFilename)
    {
        $this->gifFileName = $gifFilename;
    }
}

