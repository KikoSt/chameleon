<?php

class ProductModel
{
    private $productId;
    private $feedId;
    private $categoryId;
    private $currencyId;

    private $ean;
    private $isbn;

    private $name;
    private $productUrl;
    private $imageUrl;
    private $description;
    private $price;
    private $priceOld;

    private $aggregationNumber;

    private $shipping;
    private $promotionStartDate;
    private $promotionEndDate;

    private $productSize;
    private $gender;
    private $color;

    private $currencyShort;
    private $currencySymbol;

    public function __construct()
    {
    }



    /**
     * Set priceOld.
     *
     * @param priceOld the value to set.
     */
    public function setPriceOld($priceOld)
    {
        $this->priceOld = $priceOld;
    }

    /**
     * Get priceOld.
     *
     * @return priceOld.
     */
    public function getPriceOld()
    {
        return $this->priceOld;
    }

    /**
     * Set shipping.
     *
     * @param shipping the value to set.
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * Get shipping.
     *
     * @return shipping.
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Set promotionStartDate.
     *
     * @param promotionStartDate the value to set.
     */
    public function setPromotionStartDate($promotionStartDate)
    {
        $this->promotionStartDate = $promotionStartDate;
    }

    /**
     * Get promotionStartDate.
     *
     * @return promotionStartDate.
     */
    public function getPromotionStartDate()
    {
        return $this->promotionStartDate;
    }

    /**
     * Set promotionEndDate.
     *
     * @param promotionEndDate the value to set.
     */
    public function setPromotionEndDate($promotionEndDate)
    {
        $this->promotionEndDate = $promotionEndDate;
    }

    /**
     * Get promotionEndDate.
     *
     * @return promotionEndDate.
     */
    public function getPromotionEndDate()
    {
        return $this->promotionEndDate;
    }

    /**
     * Set productSize.
     *
     * @param productSize the value to set.
     */
    public function setProductSize($productSize)
    {
        $this->productSize = $productSize;
    }

    /**
     * Get productSize.
     *
     * @return productSize.
     */
    public function getProductSize()
    {
        return $this->productSize;
    }

    /**
     * Set gender.
     *
     * @param gender the value to set.
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender.
     *
     * @return gender.
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set color.
     *
     * @param color the value to set.
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Get color.
     *
     * @return color.
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Get price.
     *
     * @return price.
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price.
     *
     * @param price the value to set.
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get description.
     *
     * @return description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param description the value to set.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get imageUrl.
     *
     * @return imageUrl.
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set imageUrl.
     *
     * @param imageUrl the value to set.
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * Get productUrl.
     *
     * @return productUrl.
     */
    public function getProductUrl()
    {
        return $this->productUrl;
    }

    /**
     * Set productUrl.
     *
     * @param productUrl the value to set.
     */
    public function setProductUrl($productUrl)
    {
        $this->productUrl = $productUrl;
    }

    /**
     * Get name.
     *
     * @return name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param name the value to set.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get isbn.
     *
     * @return isbn.
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set isbn.
     *
     * @param isbn the value to set.
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * Get ean.
     *
     * @return ean.
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set ean.
     *
     * @param ean the value to set.
     */
    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    /**
     * Get currencyId.
     *
     * @return currencyId.
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * Set currencyId.
     *
     * @param currencyId the value to set.
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;
    }

    /**
     * Get categoryId.
     *
     * @return categoryId.
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set categoryId.
     *
     * @param categoryId the value to set.
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Get feedId.
     *
     * @return feedId.
     */
    public function getFeedId()
    {
        return $this->feedId;
    }

    /**
     * Set feedId.
     *
     * @param feedId the value to set.
     */
    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;
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

    /**
     * Get currencyShort.
     *
     * @return currencyShort.
     */
    public function getCurrencyShort()
    {
        return $this->currencyShort;
    }

    /**
     * Set currencyShort.
     *
     * @param currencyShort the value to set.
     */
    public function setCurrencyShort($currencyShort)
    {
        $this->currencyShort = $currencyShort;
    }

    /**
     * Get currencySymbol.
     *
     * @return currencySymbol.
     */
    public function getCurrencySymbol()
    {
        return $this->currencySymbol;
    }

    /**
     * Set currencySymbol.
     *
     * @param currencySymbol the value to set.
     */
    public function setCurrencySymbol($currencySymbol)
    {
        $this->currencySymbol = $currencySymbol;
    }

    public function __toString()
    {
        $string = $this->name . ' (ProductID: ' . $this->getProductId() . '): ' . $this->getPrice();
        if(!empty($this->getCurrenySymbol))
        {
            $string .= $this->getCurrencySymbol();
        }
        else
        {
            $string .= $this->getCurrencyShort();
        }
        return $string;
    }

    /**
     * Get aggregationNumber.
     *
     * @return aggregationNumber.
     */
    public function getAggregationNumber()
    {
        return $this->aggregationNumber;
    }

    /**
     * Set aggregationNumber.
     *
     * @param aggregationNumber the value to set.
     */
    public function setAggregationNumber($aggregationNumber)
    {
        $this->aggregationNumber = $aggregationNumber;
    }
}
