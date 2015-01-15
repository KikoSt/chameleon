<?php


/**
 * TemplateObject
 *
 * class representing templates as organizational units as opposed to the BannerTemplate which is not only a
 * more "technical" representation but also reflects API requirements.
 *
 * A TemplateObject can contain a BannerTemplate to make the technical information (width, height, svg, ...)
 * accessible; if those information aren't required, the bannerTemplate is not required as well.l
 *
 * @package
 * @version $id$
 * @copyright 2014 Media Decision GmbH
 * @author Christoph 'Kiko' Starkmann <christoph.starkmann@mediadecision.com>
 * @license Proprietary/Closed Source
 */
class TemplateObject
{
    private $categoryIds;
    private $companyId;
    private $advertiserId;
    private $templateId;
    private $bannerTemplate; // a bannerTemplate containing the "technical" information like width, height ...
                             // see documentation of class bannerTemplate

    public function __construct($templateId)
    {
        $this->templateId   = $templateId; // immutable after creation
        $this->categoryIds  = array();
        $this->companyId    = null;
        $this->advertiserId = null;

        $this->bannerTemplate = null;
    }



    public function addCategoryId($categoryId)
    {
        $this->categoryIds[] = $categoryId;
    }

    public function removeCategoryId($categoryId)
    {
        if(($key = array_search($categoryId, $this->categoryIds)) !== false)
        {
            unset($this->categoryIds[$key]);
        }
    }

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }

    public function getAdvertiserId()
    {
        return $this->advertiserId();
    }

    public function setBannerTemplate(BannerTemplateModel $bannerTemplate)
    {
        $this->bannerTemplate = $bannerTemplate;
    }

    public function getBannerTemplate()
    {
        return $this->bannerTemplate;
    }
}
